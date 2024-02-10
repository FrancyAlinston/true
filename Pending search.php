<?php
$folderPath = 'LOGO'; // Replace with your images directory path
$thumbnailPath = 'thumbnails'; // Replace with your thumbnails directory path
$query = isset($_POST['query']) ? strtolower($_POST['query']) : '';

$directory = new RecursiveDirectoryIterator($folderPath);
$iterator = new RecursiveIteratorIterator($directory);

// ... [The rest of your search.php code]

foreach ($iterator as $info) {
    $filename = $info->getFilename();
    // Skip directories, hidden files, and non-matching files if a search term is entered
    if ($info->isDir() || substr($filename, 0, 1) === '.' || ($query !== '' && stripos($filename, $query) === false)) {
        continue;
    }

    // Check for image file extensions
    if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $filename)) {
        $filePath = $info->getPathname();
        $thumbnailFilePath = $thumbnailPath . '/' . $filename;

        // Create thumbnail if it doesn't exist
        if (!file_exists($thumbnailFilePath)) {
            $created = createThumbnail($filePath, $thumbnailFilePath, 200, 200);
            if (!$created) {
                error_log("Failed to create a thumbnail for $filePath");
                continue; // Skip this image if the thumbnail creation failed
            }
        }

        echo '<div><img src="'.htmlspecialchars($thumbnailFilePath).'" alt="'.htmlspecialchars($filename).'" class="img-thumbnail" data-fullsize="'.htmlspecialchars($filePath).'"></div>';
    }
}
function createThumbnail($src, $dest, $targetWidth, $targetHeight) {
    list($width, $height) = getimagesize($src);
    $sourceImage = imagecreatefromstring(file_get_contents($src));
    $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

    // Resize and crop image
    imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
    imagejpeg($thumbnail, $dest, 90); // Save the thumbnail

    imagedestroy($sourceImage);
    imagedestroy($thumbnail);
}
// ... [The rest of your search.php code]
?>