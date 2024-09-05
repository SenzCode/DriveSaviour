<?php
$latitude = isset($_GET['lat']) ? $_GET['lat'] : '';
$longitude = isset($_GET['lon']) ? $_GET['lon'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map Location</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4WKu5raw64v4-CB8bYSq7SMtFikfu5lg"></script>
    <style>
        #map {
            height: 100%;
            width: 100%;
        }
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div id="map"></div>

    <script>
        function initMap() {
            const userLocation = { lat: parseFloat('<?php echo $latitude; ?>'), lng: parseFloat('<?php echo $longitude; ?>') };

            // Create map centered on user's location
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: userLocation
            });

            // Add a marker at the user's location
            new google.maps.Marker({
                position: userLocation,
                map: map
            });
        }

        window.onload = initMap;
    </script>
</body>
</html>
