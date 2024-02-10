<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Image Gallery with Thumbnails and Search</title>
    <link rel="stylesheet" href="gallery.css">
</head>
<body>

<form action="" method="get">
    <input type="text" name="search" placeholder="Search images..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Search</button>
</form>

<div class="gallery">
    <?php
    $folderPath = 'LOGO'; // Replace with your images directory path
    $thumbnailPath = 'thumbnails'; // Replace with your thumbnails directory path
    $searchTerm = isset($_GET['search']) ? strtolower($_GET['search']) : '';

    if (!file_exists($thumbnailPath)) {
        mkdir($thumbnailPath, 0755, true);
    }

    $directory = new RecursiveDirectoryIterator($folderPath);
    $iterator = new RecursiveIteratorIterator($directory);

    foreach ($iterator as $info) {
        $filename = $info->getFilename();
        // Skip directories, hidden files, and non-matching files if a search term is entered
        if ($info->isDir() || substr($filename, 0, 1) === '.' || ($searchTerm !== '' && stripos($filename, $searchTerm) === false)) {
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

            echo '<div><img src="'.htmlspecialchars($thumbnailFilePath).'" alt="'.htmlspecialchars($filename).'"></div>';
        }
    }

    // Function to create a thumbnail
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
    ?>
</div>

</body>
</html>