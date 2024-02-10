<?php
$folderPath = 'LOGO'; // Replace with your images directory path
$thumbnailPath = 'thumbnails'; // Replace with your thumbnails directory path
$query = isset($_POST['query']) ? strtolower($_POST['query']) : '';

$directory = new RecursiveDirectoryIterator($folderPath);
$iterator = new RecursiveIteratorIterator($directory);

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
            createThumbnail($filePath, $thumbnailFilePath, 200, 200);
        }

        // Output the image and zoom button
        echo '<div class="image-container">';
        echo '<img src="' . htmlspecialchars($thumbnailFilePath) . '" alt="' . htmlspecialchars($filename) . '">';
        echo '<button class="zoom-button">Zoom</button>';
        echo '</div>';
    }
}

// Function to create a thumbnail
function createThumbnail($src, $dest, $targetWidth, $targetHeight) {
    // Check if the GD library is installed
    if (!extension_loaded('gd')) {
        error_log('The GD library is not available.');
        return false;
    }

    // Get the image type
    $type = exif_imagetype($src);
    if (!$type) {
        error_log('Could not determine the image type of ' . $src);
        return false;
    }

    // Create an image resource from the original image
    switch ($type) {
        case IMAGETYPE_GIF:
            $sourceImage = imagecreatefromgif($src);
            break;
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($src);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($src);
            break;
        default:
            error_log('Unsupported image type: ' . $type);
            return false;
    }

    if (!$sourceImage) {
        error_log('Could not create image resource from ' . $src);
        return false;
    }

    // Get the dimensions of the original image
    list($width, $height) = getimagesize($src);

    // Create a new true color image with the target dimensions
    $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

    // Preserve transparency for PNG and GIF files
    if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {
        imagecolortransparent($thumbnail, imagecolorallocatealpha($thumbnail, 0, 0, 0, 127));
        imagealphablending($thumbnail, false);
        imagesavealpha($thumbnail, true);
    }

    // Copy and resize the original image into the thumbnail
    imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

    // Save the thumbnail
    switch ($type) {
        case IMAGETYPE_GIF:
            imagegif($thumbnail, $dest);
            break;
        case IMAGETYPE_JPEG:
            imagejpeg($thumbnail, $dest, 90);
            break;
        case IMAGETYPE_PNG:
            imagepng($thumbnail, $dest);
            break;
    }

    // Free up memory
    imagedestroy($sourceImage);
    imagedestroy($thumbnail);

    return true;
}
?>