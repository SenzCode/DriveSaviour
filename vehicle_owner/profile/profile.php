<?php
// Start session to access session variables
session_start();

// Include the database connection and navbar
require('../../connection.php');
require('../navbar/nav.php');

// Retrieve user information from session variables
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : '';
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$contact = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';
$city = isset($_SESSION['city']) ? $_SESSION['city'] : '';

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
    <title>ABC INSTITUTE</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <!-- Adding user profile image -->
    <div class="side-container">
        <div class="profilepic">
            <label for="profile-image-input">
                <div id="profile-image-container">
                    <img id="profile-image-preview" src="Images/user.png" alt="User Profile Icon">
                </div>
            </label>
        </div>
    </div>

    <div class="profileTop">
        <h1 class="topname"><b><span><h2>Profile and Vehicle Section</h2></span></b></h1>
        <br>
        
        <div class="p_data">
            <div class="personal_details">
                <h2> Personal Details</h2>
                <hr>
                <br>
                <div class="form-row">
                    <span class="form-label">Name:</span>
                    <span class="form-value"><?php echo $name; ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">E-mail:</span>
                    <span class="form-value"><?php echo $email; ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">Contact info:</span>
                    <span class="form-value"><?php echo $contact; ?></span>
                </div>
                <div class="form-row">
                    <span class="form-label">City:</span>
                    <span class="form-value"><?php echo $city; ?></span>
                </div>
            </div>
            <br><br>
            <button type="submit" class="btn">Update</button>

            <!-- Vehicle Details -->
            <br> <br>
            <div class="vehicle_details">
                <h2>Vehicle Details</h2>
                <hr>
                <?php if ($vehicleResult->num_rows > 0) { ?>
                    <div class="vehicle-card-container">
                        <?php while ($vehicle = $vehicleResult->fetch_assoc()) { ?>
                            <div class="vehicle-card">
                                <h3><?php echo $vehicle['model']; ?> (<?php echo $vehicle['year']; ?>)</h3>
                                <p><strong>Number Plate:</strong> <?php echo $vehicle['number_plate']; ?></p>
                                <p><strong>Fuel Type:</strong> <?php echo $vehicle['fuel_type']; ?></p>
                                <p><strong>Engine Type:</strong> <?php echo $vehicle['engine_type']; ?></p>
                                <p><strong>Tire Size:</strong> <?php echo $vehicle['tire_size']; ?></p>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <p>No vehicles found for this user.</p>
                <?php } ?>
            </div>
            <button type="button" class="btn" id="openModalBtn">Add</button>
        </div>
    </div>
</div>


<!-- Modal HTML -->
<div id="addVehicleModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add Vehicle Details</h2>
        
        <form id="vehicleForm" method="POST" action="add_vehicle.php">
            <div class="form-row">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" readonly>
            </div>

            <!-- Name field (if it's required) -->
            <div class="form-row">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" readonly>
            </div>

            <!-- Contact field (if it's required) -->
            <div class="form-row">
                <label for="contact">Phone:</label>
                <input type="text" id="contact" name="contact" value="<?php echo $contact; ?>" readonly>
            </div>

            <!-- Number Plate field -->
            <div class="form-row">
                <label for="number_plate">Number Plate:</label>
                <input type="text" id="number_plate" name="number_plate" required>
            </div>

            <!-- Model field -->
            <div class="form-row">
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" required>
            </div>

            <!-- Year field -->
            <div class="form-row">
                <label for="year">Year:</label>
                <input type="text" id="year" name="year" required>
            </div>

            <!-- Fuel Type field -->
            <div class="form-row">
                <label for="fuel_type">Fuel Type:</label>
                <input type="text" id="fuel_type" name="fuel_type" required>
            </div>

            <!-- Engine Type field -->
            <div class="form-row">
                <label for="engine_type">Engine Type:</label>
                <input type="text" id="engine_type" name="engine_type" required>
            </div>

            <!-- Tire Size field -->
            <div class="form-row">
                <label for="tire_size">Tire Size:</label>
                <input type="text" id="tire_size" name="tire_size" required>
            </div>

            <!-- Submit Button -->
            <div class="form-row">
                <button type="submit" class="btn">Submit</button>
            </div>
        </form>
    </div>
</div>


<script>
    // Get modal elements
    var modal = document.getElementById('addVehicleModal');
    var btn = document.getElementById('openModalBtn');
    var span = document.getElementsByClassName('close')[0];

    // Open the modal when the button is clicked
    btn.onclick = function() {
    modal.style.display = 'block';
    }

    // Close the modal when the 'x' is clicked
    span.onclick = function() {
    modal.style.display = 'none';
    }

    // Close the modal if the user clicks outside the modal
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

</script>

</body>
</html>