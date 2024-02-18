<!DOCTYPE html>
<html>

<head>
  <title>Cleanup Sites</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.min.css" />
  <link href="css/style.css" rel="stylesheet" type="text/css" />
  <!-- Link to Leaflet styles and custom stylesheet -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.min.js"></script>
  <!-- Include Leaflet library -->
</head>

<body>
  <div id="map" style="height: 100vh; width: 100vw; z-index: 1;"></div>
  <!-- Map container -->
  <div id="reportinst">
    <p>Click the map to set a site, press ESC to clear all. (This page is meant for PC only)</p>
  </div>
  <script>
    // Create a Leaflet map centered at [51.505, -0.09] with a zoom level of 13
    const map = L.map('map', {
      zoomControl: false,
      maxBoundsViscosity: 1
    });
    map.setView([51.505, -0.09], 13);

    var southWest = L.latLng(-89.98155760646617, -180),
        northEast = L.latLng(89.99346179538875, 180),
        bounds = L.latLngBounds(southWest, northEast);

    map.setMaxBounds(bounds);

    // Add a tile layer to the map
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
      maxZoom: 19,
      noWrap: true,
      minZoom: 4,
    }).addTo(map);

    let circle, zoomed;

    // Load marker locations from a text file
    fetch('textData/markerLocations.txt')
      .then(response => response.text())
      .then(data => {
        const lines = data.split('\n');
        for (let line of lines) {
          const [lat, lng] = line.split(' ');
          L.marker([lat, lng], {icon: markerIcon}).addTo(map);
        }
      });

    var markerIcon = L.icon({
        iconUrl: 'images/pin.png',
        shadowUrl: 'images/pinshadow.png',

        iconSize:     [66, 94], // size of the icon
        shadowSize:   [33, 16], // size of the shadow
        iconAnchor:   [33, 92], // point of the icon which will correspond to marker's location
        shadowAnchor: [16.5, 3],  // the same for the shadow
        popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
    });

    // Watch for changes in the user's location
    navigator.geolocation.watchPosition(success, error);

    function success(pos) {
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;
      const accuracy = pos.coords.accuracy;

      // Remove the previous circle layer (if any)
      if (circle) {
        map.removeLayer(circle);
      }

      // Add a new circle layer to the map
      circle = L.circle([lat, lng], {
        radius: accuracy,
        title: "Location"
      }).bindPopup('<img src="https://i.pinimg.com/originals/48/c9/52/48c9522aaa31a27582216bec737e92ce.gif" style="height: 48px; width: auto;">').addTo(map);

      // Fit the map bounds to the circle layer
      if (!zoomed) {
        zoomed = map.fitBounds(circle.getBounds());
      }

      // Set the map view to the user's location
      map.setView([lat, lng]);

      function clickinfo(lat, lng) {
        alert(`https://maps.google.com/maps/place/${lat}+${lng}`);
        window.location.href = `https://maps.google.com/maps/place/${lat}+${lng}`
          console.log('Clicked!');
        };


      // Add a marker on click
      map.off('click').on('click', function(e) {
        // console.log(e)
        console.log('Map clicked at', e.latlng);
        const marker = L.marker((e.latlng), {icon: markerIcon}).addTo(map);
        marker.on('mousedown', function(){
          clickinfo(e.latlng.lat, e.latlng.lng)
        });

        // Send a POST request to script.php with the clicked location data
        $.ajax({
          url: "script.php",
          type: "POST",
          data: { myData: e.latlng.lat + ' ' + e.latlng.lng },
          success: function(response) {
            // Handle the response from the server
            console.log(response);
            console.log('AJAX request successful:', response);
          }
        });  
      });

      // Remove markers on ESC key press
      document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
          map.eachLayer(function (layer) {
            if (layer instanceof L.Marker) {
              const latlng = layer.getLatLng();
              map.removeLayer(layer);
              deleteMatchingCoordinates(latlng.lat, latlng.lng);
            }
          });
          updateMarkerCount();
        }
      });
    }

    function error(err) {
      console.warn(`ERROR(${err.code}): ${err.message}`);
    }

    // Function to delete all coordinates that match a given lat and lng
    function deleteMatchingCoordinates(lat, lng) {
      $.ajax({
        url: "deletemarker.php",
        type: "POST",
        success: function(response) {
          // Handle the response from the server
          console.log(response);
        },
        error: function(xhr, status, error) {
            console.error("Error removing all markers: ", status, error);
        }
      });
      //   });
    }
  </script>
  <div class="navfoot">
    <button></button>
    <button></button>
    <button></button>
  </div>
  <div class="navfoot">
    <button onclick="window.location.href='/'"></button>
    <button onclick="window.location.href='/profile.php'"></button>
    <button onclick="window.location.href='/sites.php'"></button>
  </div>

</body>

</html>