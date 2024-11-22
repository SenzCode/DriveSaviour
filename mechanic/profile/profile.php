<?php
session_start();
ob_start();
require('../../connection.php');
require '../navbar/nav.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../Login/login.php");
    exit;
}

$userID = $_SESSION['userID'] ?? '';
$name = $_SESSION['name'] ?? '';
$email = $_SESSION['email'] ?? '';
$contact = $_SESSION['phone'] ?? '';
$address = $_SESSION['address'] ?? '';

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>
    <!-- Profile Section -->
    <div class="container2">
        <div class="content">
            <!-- Profile Image Section -->
            <div class="side-container">
                <div class="profilepic">
                    <div id="profile-image-container">
                        <img id="profile-image-preview" src="Images/user.png" alt="User Profile Icon">
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="profileTop">
                <h1 class="topname"><b><span><h2>Profile Section</h2></span></b></h1>
                <br>
                <div class="p_data">
                    <div class="personal_details">
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
                            <span class="form-label">address:</span>
                            <span class="form-value"><?php echo $address; ?></span>
                        </div>
                    </div>
                    <br><br>
                    <button type="submit" class="btn" id="openUpdateModalBtn">Edit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Profile Modal -->
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
                    <span for="address">address:</span>
                    <input type="text" id="update-city" name="address" value="<?php echo $address; ?>" required>
                </div>

                <div class="form-row">
                    <button type="submit" class="btn">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php
    require '../footer/footer.php';
?>
    
    <script>
        // Modal script
        var updateModal = document.getElementById('updateProfileModal');
        var updateBtn = document.getElementById('openUpdateModalBtn');
        var closeBtn = document.querySelector('.close');

        // Open modal
        updateBtn.onclick = function() {
            updateModal.style.display = 'block';
        }

        // Close modal
        closeBtn.onclick = function() {
            updateModal.style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == updateModal) {
                updateModal.style.display = 'none';
            }
        }
    </script>
</body>
</html>
