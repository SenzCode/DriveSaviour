<?php
// Start session to access session variables
session_start();

require('../../connection.php');
require '../navbar/nav.php';


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

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>

<body>

    <div class="container1">
        

        <div class="content">
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
                <h1 class="topname">
                    <b><span> <h2>Profile and Vehicle Section</h2></span></b>
                </h1>
                <br>

                <?php if ($message == 'update_prof'): ?>
                    <div class="alert alert-success" id="success-alert">Profile details updated successfully.</div>
                <?php elseif ($message == 'update_pass'): ?>
                    <div class="alert alert-success" id="success-alert">The password has been changed successfully.</div>
                <?php endif; ?>

                <div class="p_data">
                    <div class="personal_details">
                        <h2>Personal Details</h2>
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
                    <button type="submit" class="btn" id="openUpdateModalBtn">Update</button>
                    <button type="button" class="btn" id="changePasswordBtn">Change Password</button>
                </div>
                
                <!-- Change Password Modal -->
                <div id="changePasswordModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeChangePasswordModal()">&times;</span>
                        <h2>Change Password</h2>
                        <form id="changePasswordForm" method="POST" action="change_password.php">
                            <div class="form-row">
                                <label for="current-password">Current Password:</label>
                                <input type="password" id="current-password" name="current_password" required>
                            </div>
                            <div class="form-row">
                                <label for="new-password">New Password:</label>
                                <input type="password" id="new-password" name="new_password" required>
                            </div>
                            <div class="form-row">
                                <label for="confirm-password">Confirm Password:</label>
                                <input type="password" id="confirm-password" name="confirm_password" required>
                            </div>
                            <div class="form-row">
                                <button type="submit" class="btn">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>

                <br> <br>
                <!-- Vehicle Details -->
                <div class="vehicle_details">
                    <h2>Vehicle Details</h2>

                    <?php if ($message == 'delete_success'): ?>
                        <div class="alert alert-success" id="success-alert">The vehicle is deleted successfully.</div>
                    <?php elseif ($message == 'delete_fail'): ?>
                        <div class="alert alert-danger" id="success-alert">Can't delete this vehicle because there is an ongoing issue that you submitted for this vehicle</div>
                    <?php elseif ($message == 'addvehicle'): ?>
                        <div class="alert alert-success" id="success-alert">Added new vehicle successfully.</div>
                    <?php elseif ($message == 'update_success'): ?>
                        <div class="alert alert-success" id="success-alert">The vehicle details updated successfully.</div>
                    <?php endif; ?>

                    <?php if ($vehicleResult->num_rows > 0) { ?>
                        <div class="vehicle-card-container">
                            <?php while ($vehicle = $vehicleResult->fetch_assoc()) { ?>
                                <div class="vehicle-card">
                                    <h3><?php echo $vehicle['model']; ?> (<?php echo $vehicle['year']; ?>)</h3>
                                    <p><strong>Number Plate:</strong> <?php echo $vehicle['number_plate']; ?></p>
                                    <p><strong>Fuel Type:</strong> <?php echo $vehicle['fuel_type']; ?></p>
                                    <p><strong>Engine Type:</strong> <?php echo $vehicle['engine_type']; ?></p>
                                    <p><strong>Tire Size:</strong> <?php echo $vehicle['tire_size']; ?></p>
                                    <!-- Update button to open modal for this specific vehicle -->
                                    <button type="button" class="vcu" onclick="openUpdateVehicleModal(<?php echo $vehicle['v_id']; ?>)"><i class="bx bx-refresh"></i></button>
                                    <!-- Delete button -->
                                    <form method="POST" action="delete_vehicle.php" style="display:inline;">
                                        <input type="hidden" name="v_id" value="<?php echo $vehicle['v_id']; ?>">
                                        <button type="submit" class="vcd" onclick="return confirm('Are you sure you want to delete this vehicle?');"><i class="bx bx-trash"></i></button>
                                    </form>
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


     <br> <br> <br>

    <!-- Update Vehicle Modal -->
    <div id="updateVehicleModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateVehicleModal()">&times;</span>
            <h2>Update Vehicle Details</h2>

            <form id="updateVehicleForm" method="POST" action="update_vehicle.php">
                <input type="hidden" id="v-id" name="v_id">

                <div class="form-row">
                    <label for="update-model">Model:</label>
                    <input type="text" id="update-model" name="model" required>
                </div>

                <div class="form-row">
                    <label for="update-year">Year:</label>
                    <input type="text" id="update-year" name="year" required>
                </div>

                <div class="form-row">
                    <label for="update-fuel_type">Fuel Type:</label>
                    <input type="text" id="update-fuel_type" name="fuel_type" required>
                </div>

                <div class="form-row">
                    <label for="update-engine_type">Engine Type:</label>
                    <input type="text" id="update-engine_type" name="engine_type" required>
                </div>

                <div class="form-row">
                    <label for="update-tire_size">Tire Size:</label>
                    <input type="text" id="update-tire_size" name="tire_size" required>
                </div>

                <!-- Submit Button -->
                <div class="form-row">
                    <button type="submit" class="btn">Update</button>
                </div>
            </form>
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
                    <input type="text" id="number_plate" name="number_plate">
                </div>

                <!-- Model field -->
                <div class="form-row">
                    <label for="model">Model:</label>
                    <input type="text" id="model" name="model">
                </div>

                <!-- Year field -->
                <div class="form-row">
                    <label for="year">Year:</label>
                    <input type="text" id="year" name="year">
                </div>

                <!-- Fuel Type field -->
                <div class="form-row">
                    <label for="fuel_type">Fuel Type:</label>
                    <input type="text" id="fuel_type" name="fuel_type">
                </div>

                <!-- Engine Type field -->
                <div class="form-row">
                    <label for="engine_type">Engine Type:</label>
                    <input type="text" id="engine_type" name="engine_type">
                </div>

                <!-- Tire Size field -->
                <div class="form-row">
                    <label for="tire_size">Tire Size:</label>
                    <input type="text" id="tire_size" name="tire_size">
                </div>

                <!-- Submit Button -->
                <div class="form-row">
                    <button type="submit" class="btn">Submit</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Update Profile Modal HTML -->
    <div id="updateProfileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Update Profile Details</h2>

            <form id="updateProfileForm" method="POST" action="update_profile.php">
                <div class="form-row">
                    <label for="name">Name:</label>
                    <input type="text" id="update-name" name="name" value="<?php echo $name; ?>" required>
                </div>

                <div class="form-row">
                    <label for="email">Email:</label>
                    <input type="email" id="update-email" name="email" value="<?php echo $email; ?>" readonly>
                </div>

                <div class="form-row">
                    <label for="contact">Phone:</label>
                    <input type="text" id="update-contact" name="phone" value="<?php echo $contact; ?>" required>
                </div>

                <div class="form-row">
                    <label for="city">City:</label>
                    <input type="text" id="update-city" name="city" value="<?php echo $city; ?>" required>
                </div>

                <!-- Submit Button -->
                <div class="form-row">
                    <button type="submit" class="btn">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Get the modal elements
const addVehicleModal = document.getElementById("addVehicleModal");
const updateVehicleModal = document.getElementById("updateVehicleModal");
const updateProfileModal = document.getElementById("updateProfileModal");
const changePasswordModal = document.getElementById("changePasswordModal");

// Get the button elements to open the modals
const openAddModalBtn = document.getElementById("openModalBtn");
const openUpdateModalBtn = document.getElementById("openUpdateModalBtn");
const changePasswordBtn = document.getElementById("changePasswordBtn");

// Get the close button elements
const closeButtons = document.querySelectorAll(".modal .close");

// Function to open a modal
function openModal(modal) {
    modal.style.display = "block";
}

// Function to close a modal
function closeModal(modal) {
    modal.style.display = "none";
}

// Open Add Vehicle Modal
openAddModalBtn.onclick = function() {
    openModal(addVehicleModal);
};

// Open Update Profile Modal
openUpdateModalBtn.onclick = function() {
    openModal(updateProfileModal);
};

// Open Change Password Modal
changePasswordBtn.onclick = function() {
    openModal(changePasswordModal);
};

// Close Change Password Modal function
function closeChangePasswordModal() {
    closeModal(changePasswordModal);
}

// Open Update Vehicle Modal and set the vehicle ID (this can be called with the specific vehicle ID to update)
function openUpdateVehicleModal(vehicleId) {
    // Set vehicle ID in hidden input
    document.getElementById("v-id").value = vehicleId;

    // Fetch vehicle details via AJAX
    loadVehicleDetails(vehicleId);

    // Open the modal
    openModal(updateVehicleModal);
}

// Function to load vehicle details using AJAX
function loadVehicleDetails(vehicleId) {
    fetch(`get_vehicle.php?v_id=${vehicleId}`)
        .then(response => response.json())
        .then(data => {
            // Populate modal fields with vehicle data
            document.getElementById("update-model").value = data.model;
            document.getElementById("update-year").value = data.year;
            document.getElementById("update-fuel_type").value = data.fuel_type;
            document.getElementById("update-engine_type").value = data.engine_type;
            document.getElementById("update-tire_size").value = data.tire_size;
        })
        .catch(error => {
            console.error("Error fetching vehicle details:", error);
            alert("Failed to load vehicle details.");
        });
}

// Add event listeners to close buttons
closeButtons.forEach(button => {
    button.onclick = function() {
        const modal = button.closest(".modal");
        closeModal(modal);
    };
});

// Close modal when clicking outside the modal content
window.onclick = function(event) {
    if (event.target.classList.contains("modal")) {
        closeModal(event.target);
    }
};

// Close the Change Password modal if the user clicks outside of it
window.onclick = function(event) {
    if (event.target === changePasswordModal) {
        closeChangePasswordModal();
    }
};

// Hide success alert after 10 seconds
setTimeout(function() {
    var alert = document.getElementById('success-alert');
    if (alert) {
        alert.style.display = 'none';
    }
}, 10000);



    
</script>



    

    <?php
    require '../footer/footer.php';
    ?>
</body>

    <!-- <script>
        setTimeout(function() {
            var alert = document.getElementById('success-alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 10000);
    </script> -->

</html>