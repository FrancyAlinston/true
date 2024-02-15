<?php
// Set the paths to your images and thumbnails directories
$folderPath = 'LOGO';
$thumbnailPath = 'thumbnails';

// Set up your database connection
$db = new PDO('mysql:host=localhost;dbname=Members_scanned', 'root', 'admin007');


// Function to create a thumbnail
function createThumbnail($sourcePath, $thumbnailPath, $thumbWidth = 150) {
    // Check if the GD library is installed
    if (!extension_loaded('gd')) {
        error_log('The GD library is not available.');
        return false;
    }

    // Get the image size and type
    list($width, $height, $type) = getimagesize($sourcePath);
    if ($width === 0) {
        error_log('The width of the image cannot be determined.');
        return false;
    }
    $thumbHeight = floor($height * ($thumbWidth / $width));
    // Create a new true color image for the thumbnail
    $thumb = imagecreatetruecolor($thumbWidth, $thumbHeight);

    // Create the image from the source file based on the type
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($sourcePath);
            break;
        default:
            error_log('Unsupported file type.');
            return false;
    }

    // Check if the image resource was created successfully
    if (!$source) {
        error_log('Could not create image resource.');
        return false;
    }

    // Resize the source image to the thumbnail size
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);

    // Save the thumbnail to the thumbnail path
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($thumb, $thumbnailPath, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumb, $thumbnailPath);
            break;
        case IMAGETYPE_GIF:
            imagegif($thumb, $thumbnailPath);
            break;
    }

    // Free up memory
    imagedestroy($source);
    imagedestroy($thumb);

    return true;
}

// Function to synchronize the database with the image files
function synchronizeDatabaseWithFiles($db, $folderPath, $thumbnailPath) {
    // Retrieve all current records from the database
    $stmt = $db->query("SELECT filename, file_path, thumbnail_path FROM image_library");
    $currentFiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dbFiles = [];
    foreach ($currentFiles as $file) {
        $dbFiles[$file['filename']] = $file;
    }

    // Set up a new Recursive Directory Iterator
    $directory = new RecursiveDirectoryIterator($folderPath, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::SELF_FIRST);

    // Iterate over all files in the directory
    foreach ($iterator as $info) {
        $filename = $info->getFilename();

        // Skip directories and hidden files
        if ($info->isDir() || substr($filename, 0, 1) === '.') {
            continue;
        }

        // Check for image file extensions
        if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $filename)) {
            $filePath = $info->getPathname();
            $relativePath = substr($filePath, strlen($folderPath) + 1);
            $thumbnailFilePath = $thumbnailPath . '/' . $relativePath;

            // Determine if the file is new or has been updated
            $isNewOrUpdated = !isset($dbFiles[$filename]) || filemtime($filePath) > filemtime($dbFiles[$filename]['file_path']);

            // Create a thumbnail if the file is new or updated
            if ($isNewOrUpdated) {
                if (!file_exists(dirname($thumbnailFilePath))) {
                    mkdir(dirname($thumbnailFilePath), 0755, true);
                }
                $thumbnailCreated = createThumbnail($filePath, $thumbnailFilePath);
                if (!$thumbnailCreated) {
                    error_log("Failed to create thumbnail for image: " . $filename);
                    continue;
                }
            }

            // Insert or update the database record
            $stmt = $db->prepare("INSERT INTO image_library (filename, file_path, thumbnail_path) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE file_path = VALUES(file_path), thumbnail_path = VALUES(thumbnail_path)");
            $stmt->execute([$filename, $filePath, $thumbnailFilePath]);
        }
    }

    // Find and delete records for files that no longer exist
    $allFilesInDir = iterator_to_array($iterator, false);
    $allFilenamesInDir = array_map(function ($fileInfo) {
        return $fileInfo->getFilename();
    }, $allFilesInDir);

    $filesToDelete = array_diff(array_keys($dbFiles), $allFilenamesInDir);
    foreach ($filesToDelete as $filename) {
        $stmt = $db->prepare("DELETE FROM image_library WHERE filename = ?");
        $stmt->execute([$filename]);
    }
}

// Call the function to synchronize the database with the image files
synchronizeDatabaseWithFiles($db, $folderPath, $thumbnailPath);

// Close the database connection
$db = null;
?>