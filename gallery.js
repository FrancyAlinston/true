$(document).ready(function () {
  // Event delegation to handle clicks on fullscreen buttons
  $("#gallery").on("click", ".fullscreen-button", function () {
      // Create a new img element and set the source to the data-fullsize attribute
      var imgElement = document.createElement("img");
      imgElement.src = $(this).data('fullsize');
      imgElement.style.display = 'block'; // Ensure the image is visible

      // Call the function to toggle fullscreen on this img element
      toggleFullscreen(imgElement);
  });

  // Function to toggle fullscreen on an element
  function toggleFullscreen(imgElement) {
      // Append the image to the body so it's part of the DOM
      document.body.appendChild(imgElement);

      // Add event listener for wheel event to zoom in and out
      imgElement.addEventListener('wheel', function(event) {
          event.preventDefault(); // Prevent the default scroll behavior
          var scaleIncrement = 0.1;
          var currentScale = Number(imgElement.style.transform.replace(/[^0-9.]/g, '')) || 1;

          if (event.deltaY < 0) {
              // Zoom in
              currentScale += scaleIncrement;
          } else {
              // Zoom out
              currentScale = Math.max(1, currentScale - scaleIncrement); // Don't zoom out beyond the original scale
          }

          imgElement.style.transform = `scale(${currentScale})`; // Apply the new scale
      });

      if (!document.fullscreenElement) {
          if (imgElement.requestFullscreen) {
              imgElement.requestFullscreen().catch(e => {
                  console.error("Error attempting to enable full-screen mode:", e);
              });
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

      // Remove the image from the DOM once fullscreen is exited
      imgElement.onfullscreenchange = function (event) {
          if (!document.fullscreenElement) {
              document.body.removeChild(imgElement);
          }
      };
  }

  // Live search functionality
  $("#search").keyup(function () {
      var query = $(this).val();
      if (query != "") {
          $.ajax({
              url: "search.php", // This should point to the PHP file that handles the search. If it's also renamed, update accordingly.
              method: "POST",
              data: { query: query },
              success: function (data) {
                  $("#gallery").html(data);
              },
          });
      } else {
          // Optionally, restore the gallery to its original state when the search box is cleared
      }
  });

  // Event listener for the Esc key to exit fullscreen
  $(document).on("keydown", function (event) {
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
  $("#gallery").on("load", "img", function () {
      $(this).addClass("loaded");
  });
});