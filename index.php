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
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script> -->

    <!-- LazySizes (optional for lazy loading) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async=""></script>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="gallery.js"></script>
</head>

<body>

    <div class="header-container">
        <img src="logo.png" alt="Logo" class="logo">
        <div class="search-container">
            <input type="text" id="search" placeholder="Search Member..." autocomplete="off">
            <button id="search-button" type="button">Search</button>
            <!-- Login button -->
            <!-- <button id="login-button" type="button">Login</button> -->
            <!-- Hidden password input and admin buttons -->
            <!-- <div id="admin-controls" class="hidden"> -->
                <!-- <input type="password" id="admin-password" placeholder="Enter Admin Password"> -->
                <!-- <button id="submit-password" type="button">Submit</button> -->
                <!-- <button id="update-library"  type="button" class="admin-button ">Update Image Library</button> -->
                <!-- <button id="create-thumbnails" type="button" class="admin-button">Create Thumbnails</button> -->
            <!-- </div> -->
        </div>
    </div>

    <div id="gallery" class="gallery">
        <!-- The gallery images will be displayed here -->
    </div>

</body>

</html>