<?php
session_start();
require('../navbar/nav.php');
require('../../connection.php');

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$contact = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="post-container">
        <div class="order-header">
                <button class="back-btn" onclick="window.location.href='../mech/mech.php'">&larr; Back</button>
            </div>
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

    <div id="vehicleModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 class="topic">Confirm Vehicle Details</h2>
            <form id="issueForm" method="POST" action="submit_vehicleissue.php">
                <input type="hidden" id="year" name="year">
                <input type="hidden" id="number_plate" name="number_plate">
                <input type="hidden" id="fuel_type" name="fuel_type">
                <input type="hidden" id="engine_type" name="engine_type">
                <input type="hidden" id="tire_size" name="tire_size">
                <input type="hidden" id="location" name="location">

                <div class="form-row">
                    <label for="model">Model:</label>
                    <input type="text" id="model" name="model" readonly>
                </div>

                <div class="form-row">
                    <label for="vehicle_issue">Describe the Issue:</label>
                    <textarea id="vehicle_issue" name="vehicle_issue" required></textarea>
                </div>
                
                <div class="form-row">
                    <label for="city">Near City:</label>
                    <input type="text" id="city" name="city">
                </div>

                <div class="form-row">
                    <button type="submit" class="btn" id="shareLocationBtn">Submit</button>
                </div>
            </form>


        </div>
    </div> <br> <br> <br>

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
    $('#shareLocationBtn').click(function(event) {
        event.preventDefault();  // Prevent the default form submission

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                // Get user's latitude and longitude
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                // Set the location input field with the lat and long values
                $('#location').val(`${latitude}, ${longitude}`);

                // Now that location is set, submit the form
                $('#issueForm').submit();
            }, function(error) {
                alert("Error fetching location: " + error.message);
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    });
});
</script>

<?php
    require '../footer/footer.php';
?>

</body>
</html>
