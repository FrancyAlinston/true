<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Image Gallery with Live Search</title>
    <link rel="stylesheet" href="gallery.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="gallery.js"></script>
</head>
<body>
    <div class="search-container">
        <input type="text" id="search" placeholder="Search images..." autocomplete="off">
    </div>
    <div id="gallery" class="gallery">
        <!-- The gallery images will be displayed here -->
    </div>
</body>
</html>