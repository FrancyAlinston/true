<?php
// Set the paths to your images and thumbnails directories
$folderPath = 'LOGO';
$thumbnailPath = 'thumbnails';

// Set up your database connection
$db = new PDO('mysql:host=localhost;dbname=Members_scanned', 'root', 'admin007');

// Function to create a thumbnail
function createThumbnail($sourcePath, $thumbnailPath, $thumbWidth = 150) {
    // Get the image size and type
    list($width, $height, $type) = getimagesize($sourcePath);
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
            return false; // Unsupported file type
    }

    // Resize the source image to the thumbnail size
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);

    // Save the thumbnail to the thumbnail path
    imagejpeg($thumb, $thumbnailPath, 90); // Adjust compression quality as needed

    // Free up memory
    imagedestroy($source);
    imagedestroy($thumb);

    return true;
}

// Set up a new Recursive Directory Iterator
$directory = new RecursiveDirectoryIterator($folderPath);
$iterator = new RecursiveIteratorIterator($directory);

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
        $thumbnailFilePath = $thumbnailPath . '/' . $filename;

        // Create a thumbnail if it doesn't exist or if the original image is newer
        if (!file_exists($thumbnailFilePath) || filemtime($filePath) > filemtime($thumbnailFilePath)) {
            createThumbnail($filePath, $thumbnailFilePath);
        }

        // Remove leading zeros from the filename before the hyphen
        $filenameParts = explode('-', $filename, 2);
        $filenameWithoutLeadingZeros = ltrim($filenameParts[0], '0');
        if (isset($filenameParts[1])) {
            $filenameWithoutLeadingZeros .= '-' . $filenameParts[1];
        }

        // Prepare the SQL statement with IGNORE to skip duplicates
        $stmt = $db->prepare("INSERT IGNORE INTO image_library (filename, file_path, thumbnail_path) VALUES (?, ?, ?)");

        // Execute the statement with the file paths
        $stmt->execute([$filenameWithoutLeadingZeros, $filePath, $thumbnailFilePath]);
    }
}

// Close the database connection
$db = null;
?>