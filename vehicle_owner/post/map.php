<?php
session_start();
require('../../connection.php');
$vehicleId = isset($_GET['vehicleId']) ? $_GET['vehicleId'] : '';

$latitude = '';
$longitude = '';

if ($vehicleId) {
    $query = "SELECT location FROM vehicleissues WHERE vehicleId = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $vehicleId);
        $stmt->execute();
        $stmt->bind_result($location);
        if ($stmt->fetch()) {
            list($latitude, $longitude) = explode(',', $location);
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map Location</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4WKu5raw64v4-CB8bYSq7SMtFikfu5lg"></script> <!-- Replace YOUR_API_KEY with your actual API key -->
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
            const latitude = parseFloat('<?php echo $latitude; ?>');
            const longitude = parseFloat('<?php echo $longitude; ?>');
            if (isNaN(latitude) || isNaN(longitude)) {
                console.error('Invalid latitude or longitude');
                return;
            }

            const userLocation = { lat: latitude, lng: longitude };
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: userLocation
            });

            new google.maps.Marker({
                position: userLocation,
                map: map
            });
        }

        window.onload = initMap;
    </script>
</body>
</html>
