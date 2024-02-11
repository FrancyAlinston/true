<?php
// Database configuration
$dbHost = 'localhost';
$dbUsername = 'alen';
$dbPassword = '2020';
$dbName = 'navadarsandev';

// Connect to the database
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check for database connection errors
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$folderPath = 'LOGO'; // Replace with your images directory path

// Scan the directory for image files
$images = glob($folderPath . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

// Get all filenames from the database
$dbFilesResult = $db->query("SELECT filename FROM images");
$dbFiles = [];
while ($row = $dbFilesResult->fetch_assoc()) {
    $dbFiles[] = $row['filename'];
}

// Check for new or deleted files
$filesToAdd = array_diff($images, $dbFiles);
$filesToDelete = array_diff($dbFiles, $images);

// Add new images to the database
foreach ($filesToAdd as $filePath) {
    $filename = basename($filePath);
    $parts = explode('-', $filename);
    $memberId = $parts[0];
    // Remove the file extension from benefactorId
    $benefactorId = preg_replace('/\.[^.]+$/', '', $parts[1]);

    $stmt = $db->prepare("INSERT INTO images (filename, member_id, benefactor_id) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $filename, $memberId, $benefactorId);
    $stmt->execute();
}

// Delete removed images from the database
foreach ($filesToDelete as $filename) {
    $stmt = $db->prepare("DELETE FROM images WHERE filename = ?");
    $stmt->bind_param('s', $filename);
    $stmt->execute();
}

// Close the database connection
$db->close();

// Return a response (optional)
echo "Synchronization complete.";
?>