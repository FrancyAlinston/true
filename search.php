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

        // Output the image
        echo '<div class="image-container">';
        echo '<img src="' . htmlspecialchars($thumbnailFilePath) . '" alt="' . htmlspecialchars($filename) . '">';
        echo '</div>';
    }
}

// Function to create a thumbnail
// ... (rest of the createThumbnail function)
?>