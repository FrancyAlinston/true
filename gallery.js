$(document).ready(function () {
  // Live search functionality
  $("#search").keyup(function() {
      var query = $(this).val();
      if (query != "") {
          $.ajax({
              url: "search.php",
              method: "POST",
              data: { query: query },
              success: function(data) {
                  $("#gallery").html(data);
              }
          });
      } else {
          // Optionally, restore the gallery to its original state when the search box is cleared
      }
  });

  // Event delegation to handle clicks on images for fullscreen
  $("#gallery").on("click", "img", function() {
      // Retrieve the associated full-size image source from the data attribute
      var fullSizeSrc = $(this).data('fullsize');
      if (fullSizeSrc) {
          var imgElement = document.createElement("img");
          imgElement.src = fullSizeSrc; // Set the source to the full-size image
          imgElement.style.display = 'block'; // Ensure the image is visible

          // Call the function to toggle fullscreen on this img element
          toggleFullscreen(imgElement);
      } else {
          console.error("Full-size image source not found.");
      }
  });

  // Function to toggle fullscreen on an element
  function toggleFullscreen(imgElement) {
      // Append the image to the body so it's part of the DOM
      document.body.appendChild(imgElement);

      if (!document.fullscreenElement) {
          if (imgElement.requestFullscreen) {
              imgElement.requestFullscreen().catch(e => {
                  console.error("Error attempting to enable full-screen mode:", e);
              });
          } else {
              // Fallback for browsers that don't support requestFullscreen
              console.error("Fullscreen API is not supported by this browser.");
          }
      } else {
          if (document.exitFullscreen) {
              document.exitFullscreen();
          }
      }

      // Remove the image from the DOM once fullscreen is exited
      imgElement.onfullscreenchange = function() {
          if (!document.fullscreenElement) {
              document.body.removeChild(imgElement);
          }
      };
  }

  // Event listener for the Esc key to exit fullscreen
  $(document).on("keydown", function(event) {
      if (event.key === "Escape" && document.fullscreenElement) {
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
  });

  // Apply 'loaded' class to images after they have loaded
  $("#gallery").on("load", "img", function() {
      $(this).addClass("loaded");
  });
});