<?php
require '../navbar/nav.php';
require '../../connection.php';

// Get the issue ID from the URL
$issue_id = intval($_GET['id']);

// Fetch issue details along with the vehicle owner's city from the database
$sql = "SELECT vi.*, vo.city 
from vehicleissues vi JOIN vehicle v ON vi.v_id = v.v_id
    JOIN vehicle_owner vo ON v.email = vo.email
    WHERE vi.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $issue_id);
$stmt->execute();
$result = $stmt->get_result();
$issue = $result->fetch_assoc();

$location = explode(',', $issue['location']);
$latitude = trim($location[0]);
$longitude = trim($location[1]);

$stmt->close();
$conn->close();

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Details</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../vehicle_owner/profile/style.css">

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4WKu5raw64v4-CB8bYSq7SMtFikfu5lg"></script>
</head>
<body>
<div class="alert_container">
    <?php if ($message == 'insert'): ?>
        <div class="alert alert-success" id="success-alert">The issue is submitted successfully.</div>
    <?php endif; ?>
</div>
<div class="issue_container">
    <div class="issue-details">
        <h1>Issue Details</h1>
        <p><strong>Email:</strong> <?= htmlspecialchars($issue['email']); ?></p>
        <p><strong>Vehicle Model:</strong> <?= htmlspecialchars($issue['vehicle_model']); ?></p>
        <p><strong>Year:</strong> <?= htmlspecialchars($issue['year']); ?></p>
        <p><strong>Mobile Number:</strong> <?= htmlspecialchars($issue['mobile_number']); ?></p>
        <p><strong>Vehicle Issue:</strong> <?= htmlspecialchars($issue['vehicle_issue']); ?></p>
        <p><strong>City:</strong> <?= htmlspecialchars($issue['city']); ?></p>

        <button class="btn accept-btn">Accept</button>
        <button class="btn decline-btn">Decline</button>
    </div>

    <div class="map-container">
        <br>
        <div id="map" style="width:100%; height:400px;"></div>
    </div>
</div>

<!-- Modal Structure -->
<div id="confirmModal" class="modal" style="display:none;">
    <div class="modal-content">
        <h4>Confirmation</h4>
        <p>Are you sure you want to accept this issue?</p>
        <form action="accept_issue.php" method="POST">
            <input type="hidden" name="issue_id" value="<?= $issue_id; ?>">
            <button type="submit" class="btn accept-btn">Yes</button>
            <button type="submit" class="btn accept-btn">No</button>
        </form>
    </div>
</div>

<script>
// Initialize Google Maps
function initMap() {
    // Destination location (database location)
    var destination = { lat: <?= $latitude ?>, lng: <?= $longitude ?> };

    // Initialize the map centered at the destination
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: destination
    });

    // Add a marker at the destination location
    var destinationMarker = new google.maps.Marker({
        position: destination,
        map: map,
        title: 'Destination'
    });

    // Directions service and renderer to display the route
    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer({
        map: map
    });

    // Marker for the user's real-time location
    var userLocationMarker = new google.maps.Marker({
        map: map,
        title: 'Your Location',
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 8,
            fillColor: '#00f',
            fillOpacity: 0.8,
            strokeColor: '#00f',
            strokeWeight: 2
        }
    });

    // Watch the user's current location
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(function (position) {
            var currentLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            // Update the user's marker position without affecting map zoom or center
            userLocationMarker.setPosition(currentLocation);

            // Set up the directions request
            var request = {
                origin: currentLocation, // Start point (user's real-time location)
                destination: destination, // End point (stored location)
                travelMode: 'DRIVING'
            };

            // Get and display the route
            directionsService.route(request, function (result, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                } else {
                    console.warn('Could not calculate route: ' + status);
                }
            });
        }, function (error) {
            console.error('Error watching location: ', error.message);
            alert('Geolocation failed. Unable to retrieve your location.');
        }, {
            enableHighAccuracy: true, // Get a more accurate location
            timeout: 5000,            // Set timeout
            maximumAge: 0             // Disable cache for real-time updates
        });
    } else {
        alert('Geolocation is not supported by this browser.');
    }
}

// Initialize the map when the page loads
window.onload = initMap;




// Modal Logic
document.querySelector('.accept-btn').addEventListener('click', function() {
    // Show the modal when Accept button is clicked
    document.getElementById('confirmModal').style.display = 'flex';
});

document.getElementById('confirmNo').addEventListener('click', function() {
    // Hide the modal when No is clicked
    document.getElementById('confirmModal').style.display = 'none';
});


</script>

<?php
    require '../../vehicle_owner/footer/footer.php';
?>
</body>
</html>
