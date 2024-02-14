<?php
// Set up your database connection
$db = new PDO('mysql:host=localhost;dbname=Members_scanned', 'root', 'admin007');

$query = isset($_POST['query']) ? strtolower($_POST['query']) : '';

// Prepare the SQL statement to search for images that match the query
// This assumes that the filename is structured as '12345-filename.jpg'
$stmt = $db->prepare("SELECT * FROM image_library WHERE LOWER(SUBSTRING_INDEX(filename, '-', 1)) LIKE ?");
$stmt->execute(["%$query%"]);

// Fetch all matching records
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($images as $image) {
    // Output the image with a fullscreen button and file name without the file extension
    echo '<div class="image-container">';
    echo '<img src="' . htmlspecialchars($image['thumbnail_path']) . '" alt="' . htmlspecialchars($image['filename']) . '" data-fullsize="' . htmlspecialchars($image['file_path']) . '">';
    $filenameWithoutExt = pathinfo($image['filename'], PATHINFO_FILENAME); // Get the filename without the extension
    echo '<div class="image-caption">' . htmlspecialchars($filenameWithoutExt) . '</div>'; // Display the file name without the extension
    echo '</div>';
}

// Close the database connection
$db = null;
?>