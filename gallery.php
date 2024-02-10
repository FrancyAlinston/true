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
            }
        });

        // Event delegation to handle clicks on images for fullscreen toggle
        $('#gallery').on('click', 'img', function() {
            var imgElement = $(this).get(0); // Get the DOM element of the clicked image
            toggleFullscreen(imgElement);
        });
    });

    // Function to toggle fullscreen on an element
    function toggleFullscreen(imgElement) {
        if (!document.fullscreenElement) {
            if (imgElement.requestFullscreen) {
                imgElement.requestFullscreen();
            } else if (imgElement.mozRequestFullScreen) { /* Firefox */
                imgElement.mozRequestFullScreen();
            } else if (imgElement.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
                imgElement.webkitRequestFullscreen();
            } else if (imgElement.msRequestFullscreen) { /* IE/Edge */
                imgElement.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.mozCancelFullScreen) { /* Firefox */
                document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) { /* Chrome, Safari & Opera */
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) { /* IE/Edge */
                document.msExitFullscreen();
            }
        }
    }
    </script>
</head>
<body>

<input type="text" id="search" placeholder="Search images..." autocomplete="off">

<div id="gallery" class="gallery">
    <!-- The gallery images will be displayed here -->
</div>

</body>
</html>