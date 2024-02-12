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
                        data: {
                            query: query
                        },
                        success: function(data) {
                            $('#gallery').html(data);
                        }
                    });
                } else {
                    // Optionally, restore the gallery to its original state when the search box is cleared
                }
            });

            // Event delegation to handle clicks on fullscreen buttons
            $('#gallery').on('click', '.fullscreen-button', function() {
                var imgElement = document.createElement("img");
                imgElement.src = $(this).data('fullsize'); // Get the original image source
                toggleFullscreen(imgElement);
            });

            // Event listener for the Esc key to exit fullscreen
            $(document).on('keydown', function(event) {
                if (event.key === "Escape" && document.fullscreenElement) {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    } else if (document.mozCancelFullScreen) {
                        /* Firefox */
                        document.mozCancelFullScreen();
                    } else if (document.webkitExitFullscreen) {
                        /* Chrome, Safari & Opera */
                        document.webkitExitFullscreen();
                    } else if (document.msExitFullscreen) {
                        /* IE/Edge */
                        document.msExitFullscreen();
                    }
                }
            });
        });

        // Function to toggle fullscreen on an element
        function toggleFullscreen(imgElement) {
            // Append the image to the body temporarily
            imgElement.style.display = 'none'; // Hide the image element
            document.body.appendChild(imgElement);

            // Listen for fullscreen change events
            document.addEventListener("fullscreenchange", onFullScreenChange);
            document.addEventListener("webkitfullscreenchange", onFullScreenChange);
            document.addEventListener("mozfullscreenchange", onFullScreenChange);
            document.addEventListener("MSFullscreenChange", onFullScreenChange);

            function onFullScreenChange() {
                if (!document.fullscreenElement && !document.webkitIsFullScreen && !document.mozFullScreen && !document.msFullscreenElement) {
                    imgElement.classList.remove('fullscreen-zoom'); // Remove zoom effect
                    document.body.removeChild(imgElement); // Remove the image after exiting fullscreen
                    // Remove event listeners when fullscreen is exited
                    document.removeEventListener("fullscreenchange", onFullScreenChange);
                    document.removeEventListener("webkitfullscreenchange", onFullScreenChange);
                    document.removeEventListener("mozfullscreenchange", onFullScreenChange);
                    document.removeEventListener("MSFullscreenChange", onFullScreenChange);
                } else {
                    imgElement.classList.add('fullscreen-zoom'); // Add zoom effect
                }
            }

            if (!document.fullscreenElement) {
                if (imgElement.requestFullscreen) {
                    imgElement.requestFullscreen().then(() => {
                        imgElement.style.display = 'block'; // Show the image after entering fullscreen
                    });
                } else if (imgElement.mozRequestFullScreen) {
                    /* Firefox */
                    imgElement.mozRequestFullScreen().then(() => {
                        imgElement.style.display = 'block';
                    });
                } else if (imgElement.webkitRequestFullscreen) {
                    /* Chrome, Safari & Opera */
                    imgElement.webkitRequestFullscreen().then(() => {
                        imgElement.style.display = 'block';
                    });
                } else if (imgElement.msRequestFullscreen) {
                    /* IE/Edge */
                    imgElement.msRequestFullscreen().then(() => {
                        imgElement.style.display = 'block';
                    });
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    /* Firefox */
                    document.mozCancelFullScreen();
                } else if (document.webkitExitFullscreen) {
                    /* Chrome, Safari & Opera */
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    /* IE/Edge */
                    document.msExitFullscreen();
                }
            }
        }
    </script>
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