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
  
  <div id="sitenotification">
    <p id="markerCount">Select a pin to register.</p>
    <div id="popupInfo">
      <a style="font-size: 6em;" id="backlink" href="">Back</a><br>
      <a style="font-size: 6em;" id="cleanuplink" href="">Start Cleanup</a><br>
      <a style="font-size: 6em;" id="locationlink" href="">See On Map</a>
    </div>
    <div id="pulley"></div>
  </div>
    
  <script>
    var infopopup = false;
    
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

    function clickinfo(lat, lng) {
      infopopup = true
      infoOpen();
      // alert(`https://maps.google.com/maps/place/${lat}+${lng}`);
      const currentZoomLevel = map.getZoom();
      document.getElementById('locationlink').href = `https://maps.google.com/maps/place/${lat}+${lng}`;
      document.getElementById('backlink').href = `javascript:infopopup=false;infoOpen();map.flyTo([${lat}, ${lng}], ${currentZoomLevel})`;
      document.getElementById('cleanuplink').href = `/cleanup.php`;
      console.log(document.getElementById('backlink').href)
      map.flyTo([lat, lng], 19, { animate: true, duration: 1 });

    //   console.log('Clicked!');
    };

    fetch('textData/markerLocations.txt')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(data => {
        const lines = data.split('\n').filter(line => line.trim() !== '');
        for (let line of lines) {
            console.log(`line: ${line}`);  // Add this line
            const splitLine = line.split(' ');
            console.log(`splitLine: ${splitLine}`);  // Add this line
            const [lat, lng] = splitLine;
            let marker = L.marker([lat, lng], {icon: markerIcon}).addTo(map);
            marker.on('click', function(e){
                clickinfo(e.latlng.lat, e.latlng.lng)
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
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
      }).bindPopup('<img src="https://i.pinimg.com/originals/48/c9/52/48c9522aaa31a27582216bec737e92ce.gif" style="height: 96px; width: auto;">').addTo(map);

      // Fit the map bounds to the circle layer
      if (!zoomed) {
        zoomed = map.fitBounds(circle.getBounds());
      }

      // Set the map view to the user's location
      map.setView([lat, lng])
    }

    function error(err) {
      console.warn(`ERROR(${err.code}): ${err.message}`);
    }

    fitTextToParent('#sitenotification', '#markerCount');

    function fitTextToParent(parent, child) {
      var parentHeight = $(parent).height();
      var childHeight = $(child).height();

      while (childHeight > 0.75*parentHeight) {
        var currentFontSize = parseInt($(child).css('font-size'));
        $(child).css('font-size', (currentFontSize - 1) + 'px');
        childHeight = $(child).height();
      }
    }

    fitTextToParent('#sitenotification', '#markerCount');

    infopopup = false;
    infoOpen();

    function infoOpen() {
        var popupInfo = document.getElementById('popupInfo');
        if (infopopup) {
            console.log(infopopup)
            document.getElementById('sitenotification').style.height = '30vh';
            document.getElementById('markerCount').style.display = 'none';
            popupInfo.style.display = 'block';
            isPulleyDown = false;
        } else {
            console.log(infopopup)
            document.getElementById('sitenotification').style.height = '15vh';
            document.getElementById('markerCount').style.display = 'block';
            popupInfo.style.display = 'none';
        }
    }

var isPulleyDown = true;
    
  function togglePulley() {
      var notification = document.getElementById('sitenotification');
      var pulley = document.getElementById('pulley');

      if (notification.style.height !== '0px' || isPulleyDown) {
          notification.style.height = '0';
          pulley.style.position = 'absolute';
          pulley.style.bottom = "-1.5vh";
          isPulleyDown = false;
      } else if (notification.style.height == '0px' || !isPulleyDown) {
          notification.style.height = '15vh'; // reset to default height
          pulley.style.position = '';
          pulley.style.bottom = "-7.5%";
          isPulleyDown = true;
      }
  }

  function toggleTextElements() {
      var textElements = document.querySelectorAll('#sitenotification p, #sitenotification a');

      textElements.forEach(function(element) {
          if (element.style.display !== 'none') {
              element.style.display = 'none';
          } else {
              element.style.display = 'block';
          }
      });
  }

  document.getElementById('pulley').addEventListener('click', function() {
      var notification = document.getElementById('sitenotification');

      // If the notification height is '30vh', return early and don't execute the rest of the function
      if (notification.style.height !== '30vh') {
          togglePulley();
          toggleTextElements();
      }
  });
  </script>
  <div class="navfoot">
    <button onclick="window.location.href='/'"></button>
    <button onclick="window.location.href='/profile.php'"></button>
    <button onclick="window.location.href='/sites.php'"></button>
  </div>
  
</body>

</html>