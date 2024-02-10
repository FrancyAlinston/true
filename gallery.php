<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Image Gallery with Live Search</title>
    <link rel="stylesheet" href="gallery.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#search').keyup(function() {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: "search.php",
                    method: "POST",
                    data: {query: query},
                    success: function(data) {
                        $('#gallery').html(data);
                    }
                });
            } else {
                // Optionally, restore the gallery to its original state when the search box is cleared
                // You can do this by calling the AJAX function again with an empty query
                // or by storing the original state on page load and restoring it here.
            }
        });

        // Event delegation to handle clicks on zoom buttons
        $('#gallery').on('click', '.zoom-button', function() {
            var src = $(this).siblings('img').attr('src');
            $('#zoomedImg').attr('src', src);
            $('#imageOverlay').fadeIn();
            return false; // Prevent event bubbling
        });
    });

    function closeOverlay() {
        $('#imageOverlay').fadeOut();
    }
    </script>
</head>
<body>

<input type="text" id="search" placeholder="Search images..." autocomplete="off">

<div id="gallery" class="gallery">
    <!-- The gallery images will be displayed here -->
</div>

<!-- Overlay for zoomed image -->
<div id="imageOverlay" class="overlay" onclick="closeOverlay()">
    <div class="overlay-content">
        <img id="zoomedImg" class="zoomed-image" src="" alt="Zoomed">
    </div>
</div>

</body>
</html>