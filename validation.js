function searchImages() {
    var searchQuery = document.getElementById('search').value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'search.php', true);
    xhr.setRequestHeader('Content-Type', 'search.php');
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var results = JSON.parse(xhr.responseText);
        var output = '';
        for (var i = 0; i < results.length; i++) {
          output += '<div class="max-w-sm rounded overflow-hidden shadow-lg"><img class="w-full" src="' + results[i] + '" alt="Image with ID ' + searchQuery + '"></div>';
        }
        document.getElementById('searchResults').innerHTML = output;
      }
    };
    xhr.send('id=' + encodeURIComponent(searchQuery));
  }