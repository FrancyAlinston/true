$(document).ready(function () {
  $("#search").keyup(function () {
    var query = $(this).val();
    if (query != "") {
      $.ajax({
        url: "search.php",
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

  // Event delegation to handle clicks on images for fullscreen
  $("#gallery").on("click", "img", function () {
    // Use the clicked image for fullscreen
    toggleFullscreen(this);
  });

  // Function to toggle fullscreen on an element
  function toggleFullscreen(imgElement) {
    if (!document.fullscreenElement) {
      if (imgElement.requestFullscreen) {
        imgElement.requestFullscreen();
      } else if (imgElement.mozRequestFullScreen) {
        /* Firefox */
        imgElement.mozRequestFullScreen();
      } else if (imgElement.webkitRequestFullscreen) {
        /* Chrome, Safari & Opera */
        imgElement.webkitRequestFullscreen();
      } else if (imgElement.msRequestFullscreen) {
        /* IE/Edge */
        imgElement.msRequestFullscreen();
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

  // Event listener for the Esc key to exit fullscreen
  $(document).on("keydown", function (event) {
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

  // Apply 'loaded' class to images after they have loaded
  $("#gallery").on("load", "img", function () {
    $(this).addClass("loaded");
  });
});
