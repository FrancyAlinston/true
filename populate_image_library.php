<?php
// Set the paths to your images and thumbnails directories
$folderPath = 'LOGO';
$thumbnailPath = 'thumbnails';

// Set up a new Recursive Directory Iterator
$directory = new RecursiveDirectoryIterator($folderPath);
$iterator = new RecursiveIteratorIterator($directory);

// Set up your database connection
$db = new PDO('mysql:host=localhost;dbname=Members_scanned', 'root', 'admin007');

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