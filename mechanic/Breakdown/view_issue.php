<?php
require '../navbar/nav.php';
require '../../connection.php';

// Get the issue ID from the URL
$issue_id = intval($_GET['id']);

// Fetch issue details from the database
$sql = "SELECT * FROM vehicleissues WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $issue_id);
$stmt->execute();
$result = $stmt->get_result();
$issue = $result->fetch_assoc();

// Split the location into latitude and longitude
$location = explode(',', $issue['location']);
$latitude = trim($location[0]);
$longitude = trim($location[1]);

// Close connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Details</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../vehicle_owner/profile/style.css">

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4WKu5raw64v4-CB8bYSq7SMtFikfu5lg"></script> <!-- Replace with your Google Maps API Key -->
</head>
<body>

<div class="issue_container">
    <div class="issue-details">
        <h3>Issue Details</h3>
        <!-- <p><strong>Name:</strong> <?= htmlspecialchars($issue['first_name']) . ' ' . htmlspecialchars($issue['last_name']); ?></p> -->
        <p><strong>Email:</strong> <?= htmlspecialchars($issue['email']); ?></p>
        <p><strong>Vehicle Model:</strong> <?= htmlspecialchars($issue['vehicle_model']); ?></p>
        <p><strong>Year:</strong> <?= htmlspecialchars($issue['year']); ?></p>
        <p><strong>Mobile Number:</strong> <?= htmlspecialchars($issue['mobile_number']); ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($issue['location']); ?></p>
        <p><strong>Vehicle Issue:</strong> <?= htmlspecialchars($issue['vehicle_issue']); ?></p>
    </div>

    <div class="map-container">
        <h3>Location Map</h3>
        <div id="map" style="width:100%; height:400px;"></div>
    </div>
</div>

<script>
    function initMap() {
        // Destination location (database location)
        var destination = { lat: <?= $latitude ?>, lng: <?= $longitude ?> };

        // Initialize the map centered at the destination
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: destination
        });

        // Add a marker at the destination location
        var marker = new google.maps.Marker({
            position: destination,
            map: map
        });

        // Directions service and renderer to display the route
        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer({
            map: map
        });

        // Get the user's current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var currentLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                // Set up the directions request
                var request = {
                    origin: currentLocation, // Start point (user's current location)
                    destination: destination, // End point (stored location)
                    travelMode: 'DRIVING' // You can change this to 'WALKING', 'BICYCLING', etc.
                };

                // Get and display the route
                directionsService.route(request, function(result, status) {
                    if (status == 'OK') {
                        directionsRenderer.setDirections(result);
                    } else {
                        alert('Could not calculate route: ' + status);
                    }
                });
            }, function() {
                alert('Geolocation failed. Unable to retrieve your location.');
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }

    // Initialize the map when the page loads
    window.onload = initMap;
</script>

</body>
</html>
