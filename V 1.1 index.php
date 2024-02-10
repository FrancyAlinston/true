<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Image Gallery</title>
    <link rel="stylesheet" href="gallery.css">
</head>
<body>

<div class="gallery">
    <?php
    $folderPath = 'LOGO/'; // Replace with your images directory path

    $directory = new RecursiveDirectoryIterator($folderPath);
    $iterator = new RecursiveIteratorIterator($directory);

    foreach ($iterator as $info) {
        $filename = $info->getFilename();
        // Skip directories and hidden files
        if ($info->isDir() || substr($filename, 0, 1) === '.') {
            continue;
        }

        // Check for image file extensions
        if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $filename)) {
            $filePath = $info->getPathname();
            echo '<div><img src="'.htmlspecialchars($filePath).'" alt="Gallery Image"></div>';
        }
    }
    ?>
</div>

</body>
</html>