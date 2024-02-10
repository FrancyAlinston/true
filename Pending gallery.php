<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dynamic Image Gallery with Thumbnails and Enlarge Feature</title>
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
                    // You can do this by calling the AJAX function again with an empty query
                    // or by storing the original state on page load and restoring it here.
                }
            });

            // Event handler for clicking on an image to enlarge
            $('#gallery').on('click', '.img-thumbnail', function() {
                var src = $(this).attr('data-fullsize');
                $('#modal-image').attr('src', src);
                $('#image-modal').show();
            });

            // Event handler for closing the modal
            $('#image-modal').on('click', function() {
                $(this).hide();
            });
        });
    </script>
</head>

<body>

    <!-- ... [Your existing search input and gallery container] -->

    <!-- The Modal -->
    <div id="image-modal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modal-image">
        <div id="caption"></div>
    </div>

</body>

</html>