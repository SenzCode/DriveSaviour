<?php
session_start();
require('../navbar/nav.php');
require('../../connection.php');

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$contact = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';

// Fetch vehicle data for the current user
$vehicleQuery = "SELECT * FROM vehicle WHERE email = ?";
$stmt = $conn->prepare($vehicleQuery);
$stmt->bind_param("s", $email);
$stmt->execute();
$vehicleResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="../profile/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="post-container">
        <h1>Select Your Vehicle</h1>
        <br> <br>
        <div class="vehicle_details">
            <?php if ($vehicleResult->num_rows > 0) { ?>
                <div class="vehicle-card-container">
                    <?php while ($vehicle = $vehicleResult->fetch_assoc()) { ?>
                        <div class="vehicle-card">
                            <h3><?php echo $vehicle['model']; ?> (<?php echo $vehicle['year']; ?>)</h3>
                            <p><strong>Number Plate:</strong> <?php echo $vehicle['number_plate']; ?></p>
                            <p><strong>Fuel Type:</strong> <?php echo $vehicle['fuel_type']; ?></p>
                            <p><strong>Engine Type:</strong> <?php echo $vehicle['engine_type']; ?></p>
                            <p><strong>Tire Size:</strong> <?php echo $vehicle['tire_size']; ?></p>
                            
                            <!-- Choose button triggers modal -->
                            <button type="button" class="btn openModal" 
                                data-model="<?php echo $vehicle['model']; ?>" 
                                data-year="<?php echo $vehicle['year']; ?>" 
                                data-number_plate="<?php echo $vehicle['number_plate']; ?>" 
                                data-fuel_type="<?php echo $vehicle['fuel_type']; ?>" 
                                data-engine_type="<?php echo $vehicle['engine_type']; ?>" 
                                data-tire_size="<?php echo $vehicle['tire_size']; ?>">
                                Choose
                            </button>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p>No vehicles found for this user.</p>
            <?php } ?>
        </div>
    </div>

    <!-- Modal Structure -->
    <div id="vehicleModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Confirm Vehicle Details</h2>
            <form id="issueForm" method="POST" action="submit_vehicleissue.php">
                <!-- Hidden fields for vehicle details -->
                <input type="hidden" id="year" name="year">
                <input type="hidden" id="number_plate" name="number_plate">
                <input type="hidden" id="fuel_type" name="fuel_type">
                <input type="hidden" id="engine_type" name="engine_type">
                <input type="hidden" id="tire_size" name="tire_size">
                <div class="form-row">
                    <label for="model">Model:</label>
                    <input type="text" id="model" name="model" readonly>
                </div>
                <div class="form-row">
                    <label for="location">Location:</label>
                    <button type="button" id="shareLocationBtn" class="btn">Share My Location</button>
                    <input type="hidden" id="location" name="location">
                </div>


                <div class="form-row">
                    <label for="vehicle_issue">Describe the Issue:</label>
                    <textarea id="vehicle_issue" name="vehicle_issue" required></textarea>
                </div>

                <div class="form-row">
                    <button type="submit" class="btn">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Open modal on 'Choose' button click
        $('.openModal').click(function() {
            $('#model').val($(this).data('model'));
            $('#year').val($(this).data('year'));
            $('#number_plate').val($(this).data('number_plate'));
            $('#fuel_type').val($(this).data('fuel_type'));
            $('#engine_type').val($(this).data('engine_type'));
            $('#tire_size').val($(this).data('tire_size'));

            $('#vehicleModal').show();
        });

        // Close modal
        $('.close').click(function() {
            $('#vehicleModal').hide();
        });

        // Handle Share My Location button click
        $('#shareLocationBtn').click(function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    // Get user's latitude and longitude
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    // Redirect to the map page with latitude and longitude as query parameters
                    window.location.href = `map.php?lat=${latitude}&lon=${longitude}`;
                }, function(error) {
                    alert("Error fetching location: " + error.message);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });
    });
</script>

</body>
</html>
