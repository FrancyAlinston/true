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

        // Event delegation to handle clicks on fullscreen buttons
        $('#gallery').on('click', '.fullscreen-button', function() {
            var imgElement = document.createElement("img");
            imgElement.src = $(this).data('fullsize'); // Get the original image source
            toggleFullscreen(imgElement);
        });
    });

    // Function to toggle fullscreen on an element
    function toggleFullscreen(imgElement) {
        // Append the image to the body temporarily
        imgElement.style.display = 'none'; // Hide the image element
        document.body.appendChild(imgElement);

        if (!document.fullscreenElement) {
            if (imgElement.requestFullscreen) {
                imgElement.requestFullscreen().then(() => {
                    imgElement.style.display = 'block'; // Show the image after entering fullscreen
                });
            } else if (imgElement.mozRequestFullScreen) { /* Firefox */
                imgElement.mozRequestFullScreen().then(() => {
                    imgElement.style.display = 'block';
                });
            } else if (imgElement.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
                imgElement.webkitRequestFullscreen().then(() => {
                    imgElement.style.display = 'block';
                });
            } else if (imgElement.msRequestFullscreen) { /* IE/Edge */
                imgElement.msRequestFullscreen().then(() => {
                    imgElement.style.display = 'block';
                });
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen().then(() => {
                    document.body.removeChild(imgElement); // Remove the image after exiting fullscreen
                });
            } else if (document.mozCancelFullScreen) { /* Firefox */
                document.mozCancelFullScreen().then(() => {
                    document.body.removeChild(imgElement);
                });
            } else if (document.webkitExitFullscreen) { /* Chrome, Safari & Opera */
                document.webkitExitFullscreen().then(() => {
                    document.body.removeChild(imgElement);
                });
            } else if (document.msExitFullscreen) { /* IE/Edge */
                document.msExitFullscreen().then(() => {
                    document.body.removeChild(imgElement);
                });
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