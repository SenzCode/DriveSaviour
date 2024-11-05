<?php
// Start session to access session variables
session_start();
ob_start();
require('../../connection.php');
require '../navbar/nav.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../Login/login.php");
    exit;
}

// Retrieve user information from session variables
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : '';
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$contact = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';
$city = isset($_SESSION['city']) ? $_SESSION['city'] : '';

ob_end_flush();
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
 
<div class="container2">
    <div class="content">
    <!-- Adding user profile image -->
    <div class="side-container">
        <div class="profilepic">
            <div for="profile-image-input">
                <div id="profile-image-container">
                    <img id="profile-image-preview" src="Images/user.png" alt="User Profile Icon">
                </div>
            </div>
        </div>
    </div>
    <div class="profileTop">
        <h1 class="topname"><b><span><h2>Profile Section</h2></span></b></h1>
        <br>
        
        <div class="p_data">
            <div class="personal_details">
                <h2> Personal Details</h2>
                <br>
                <div class="form-row">
                    <span class="form-label">User ID:</span>
                    <span class="form-value"><?php echo $userID; ?></span>
                </div>
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

            
        </div>
    </div>
    </div>


</div> <br> <br> <br>


<!-- Update Profile Modal HTML -->
<div id="updateProfileModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Update Profile Details</h2>
       
        <form id="updateProfileForm" method="POST" action="update_profile.php">
            <div class="form-row">
                <span for="name">Name:</span>
                <input type="text" id="update-name" name="name" value="<?php echo $name; ?>" required>
            </div>
 
            <div class="form-row">
                <span for="email">Email:</span>
                <input type="email" id="update-email" name="email" value="<?php echo $email; ?>" readonly>
            </div>
 
            <div class="form-row">
                <span for="contact">Phone:</span>
                <input type="text" id="update-contact" name="phone" value="<?php echo $contact; ?>" required>
            </div>
 
            <div class="form-row">
                <span for="city">City:</span>
                <input type="text" id="update-city" name="city" value="<?php echo $city; ?>" required>
            </div>
 
            <!-- Submit Button -->
            <div class="form-row">
                <button type="submit" class="btn">Update</button>
            </div>
        </form>
    </div>
</div>

<?php
    require '../../vehicle_owner/footer/footer.php';
?>
<script>

    // Get modal elements
var updateModal = document.getElementById('updateProfileModal');
var updateBtn = document.getElementById('openUpdateModalBtn');
var closeBtn = document.getElementsByClassName('close');

// Open the modal when the button is clicked
updateBtn.onclick = function() {
    updateModal.style.display = 'block';
}

// Close the modal when the 'x' is clicked
closeBtn.onclick = function() {
    updateModal.style.display = 'none';
}

// Close the modal if the user clicks outside the modal
window.onclick = function(event) {
    if (event.target == updateModal) {
        updateModal.style.display = 'none';
    }
}


</script>


</body>
</html>