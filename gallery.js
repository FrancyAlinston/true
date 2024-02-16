$(document).ready(function () {
  // // Live search functionality
  // $("#search").keyup(function () {
  //   var query = $(this).val();
  //   if (query != "") {
  //     $.ajax({
  //       url: "search.php",
  //       method: "POST",
  //       data: { query: query },
  //       success: function (data) {
  //         $("#gallery").html(data);
  //         initGallery(); // Initialize Masonry and Lightbox
  //       },
  //     });
  //   } else {
  //     // Optionally, restore the gallery to its original state when the search box is cleared
  //   }
  // });
  $("#search").keypress(function (event) {
    if (event.keyCode === 13) {
      // 13 is the key code for the Enter key
      var query = $(this).val();
      if (query != "") {
        $.ajax({
          url: "search.php",
          method: "POST",
          data: { query: query },
          success: function (data) {
            $("#gallery").html(data);
            // Initialize Masonry and Lightbox or any other necessary scripts
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error(
              "Error during AJAX request:",
              textStatus,
              errorThrown
            );
          },
        });
      } else {
        // Optionally, restore the gallery to its original state when the search box is cleared
        $("#gallery").html(""); // Clear the gallery if needed
      }
    }
  });

  // Event handler for the "Create Thumbnails" button click
  $("#create-thumbnails").click(function () {
    // Trigger thumbnail creation via AJAX call to the thumbnail creation script
    $.ajax({
      url: "create_thumbnails.php", // This script will trigger the thumbnail creation process
      method: "POST",
      success: function (response) {
        alert("Thumbnails created successfully.");
        // Optionally, refresh the gallery to show new thumbnails
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert("An error occurred while creating thumbnails: " + textStatus);
      },
    });
  });
  // Event handler for pressing the Enter key in the search input
  $("#search").keypress(function (event) {
    if (event.keyCode === 13) {
      // 13 is the key code for the Enter key
      var query = $(this).val();
      if (query != "") {
        $.ajax({
          url: "search.php",
          method: "POST",
          data: { query: query },
          success: function (data) {
            $("#gallery").html(data);
            // Initialize Masonry and Lightbox or any other necessary scripts
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.error(
              "Error during AJAX request:",
              textStatus,
              errorThrown
            );
          },
        });
      } else {
        // Optionally, restore the gallery to its original state when the search box is cleared
        $("#gallery").html(""); // Clear the gallery if needed
      }
    }
  });

  $("#search-button").click(function () {
    var query = $("#search").val();
    if (query != "") {
      $.ajax({
        url: "search.php",
        method: "POST",
        data: { query: query },
        success: function (data) {
          $("#gallery").html(data);
          initGallery(); // Initialize Masonry and Lightbox
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("Error during AJAX request:", textStatus, errorThrown);
        },
      });
    } else {
      // Optionally, restore the gallery to its original state when the search box is cleared
      $("#gallery").html(""); // Clear the gallery if needed
    }
  });

  // Button click event to update the image library
  $("#update-library").click(function () {
    var confirmUpdate = confirm(
      "Are you sure you want to update the image library?"
    );
    if (confirmUpdate) {
      // If confirmed, send an AJAX request to populate_image_library.php
      $.ajax({
        url: "populate_image_library.php",
        type: "POST",
        success: function (response) {
          // Handle success
          alert("Image library updated successfully.");
        },
        error: function () {
          // Handle error
          alert("An error occurred while updating the image library.");
        },
      });
    }
  });

  // Initialize Masonry and Lightbox after images are loaded
  function initGallery() {
    var $gallery = $(".gallery").imagesLoaded(function () {
      $gallery.masonry({
        itemSelector: ".image-container",
        percentPosition: true,
      });
    });
    lightbox.option({
      resizeDuration: 200,
      wrapAround: true,
    });
  }

  // Event delegation to handle clicks on images for fullscreen
  $("#gallery").on("click", "img", function () {
    var fullSizeSrc = $(this).data("fullsize");
    if (fullSizeSrc) {
      var imgElement = document.createElement("img");
      imgElement.src = fullSizeSrc; // Set the source to the full-size image
      imgElement.style.display = "block"; // Ensure the image is visible

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
        imgElement.requestFullscreen().catch((e) => {
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
    imgElement.onfullscreenchange = function () {
      if (!document.fullscreenElement) {
        document.body.removeChild(imgElement);
      }
    };
  }

  // Event handler for the login button click
  // $("#login-button").click(function () {
  //   // Show the password input field when the login button is clicked
  //   $("#admin-password").removeClass("hidden");
  //   $("#submit-password").removeClass("hidden");
  // });

  // // Event handler for the submit password button click
  // $("#submit-password").click(function () {
  //   var adminPassword = "2018"; // Set your password here
  //   var inputPassword = $("#admin-password").val();
  //   if (inputPassword === adminPassword) {
  //     // Show the admin buttons if the password is correct
  //     $(".admin-button").removeClass("hidden");
  //     // Hide the password input and submit button again
  //     $("#admin-password").addClass("hidden");
  //     $("#submit-password").addClass("hidden");
  //     // Clear the password field
  //     $("#admin-password").val("");
  //   } else {
  //     alert("Incorrect password."); // Alert if the password is wrong
  //     // Optionally, hide the password input and submit button again
  //     $("#admin-password").addClass("hidden");
  //     $("#submit-password").addClass("hidden");
  //     // Clear the password field
  //     $("#admin-password").val("");
  //   }
  // });

  // Event listener for the Esc key to exit fullscreen
  $(document).on("keydown", function (event) {
    if (event.key === "Escape" && document.fullscreenElement) {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      }
    }
  });

  // Call initGallery on page load if there are already images
  initGallery();
});
