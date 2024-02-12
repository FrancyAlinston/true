<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dynamic Image Gallery with Live Search</title>
    <link rel="stylesheet" href="gallery.css">
    <!-- Masonry -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/4.2.2/masonry.pkgd.min.js"></script>

    <!-- ImagesLoaded (needed for Masonry to layout images correctly) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.4/imagesloaded.pkgd.min.js"></script>

    <!-- Lightbox2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <!-- LazySizes (optional for lazy loading) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async=""></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="gallery.js"></script>
</head>

<body>

    <div class="header-container">
        <img src="logo.png" alt="Logo" class="logo"> <!-- Logo path updated -->
        <div class="search-container" src="logo.png" alt="Logo" class="logo">
            <input type="text" id="search" placeholder="Search images..." autocomplete="off">
        </div>
    </div>

    <div id="gallery" class="gallery">
        <!-- The gallery images will be displayed here -->
    </div>

</body>

</html>