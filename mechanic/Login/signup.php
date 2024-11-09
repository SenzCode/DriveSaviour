<?php
session_start();
include_once('../../connection.php');

if (!$conn) {
    die("Connection failed.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_DEFAULT);

    // Phone number validation
    if (!preg_match('/^\d{10,11}$/', $phone)) {
        $_SESSION['message'] = "Phone number must be 10-11 digits.";
        $_SESSION['msg_type'] = "error";
        header("Location: signup.php");
        exit();
    }

    $checkEmailQuery = "SELECT * FROM mechanic WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "Email already in use. Please choose a different email.";
        $_SESSION['msg_type'] = "error";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $insertQuery = "INSERT INTO mechanic (name, phone, address, email, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('sssss', $name, $phone, $address, $email, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['message'] = "New Account added successfully.";
            $_SESSION['msg_type'] = "success";
            header("Location: signup.php");
            exit();
        } else {
            $_SESSION['message'] = "Error: " . $stmt->error;
            $_SESSION['msg_type'] = "error";
        }
    }
    $stmt->close();
    header("Location: signup.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <!-- Sign Up Section -->
        <div class="sign-in">
            <h2>Sign Up</h2>
            <div class="social-buttons">
                <a href="#"><img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google"></a>
                <a href="#"><img src="https://img.icons8.com/ios-filled/50/000000/mac-os.png" alt="Apple"></a>
                <a href="#"><img src="https://img.icons8.com/color/48/000000/facebook-new.png" alt="Facebook"></a>
            </div>
            <p>Sign up with google or email and password</p>
            <form action="signup.php" method="POST">
                <div class="input-group">
                    <input type="text" name="name" placeholder="Name" required>
                </div>
                <div class="input-group">
                    <input type="text" name="phone" placeholder="Phone" pattern="\d{10,11}" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                </div>
                <div class="input-group">
                    <input type="text" name="address" placeholder="Address" required>
                </div>
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" name="register">SIGN UP</button>
            </form>
            <p>Already have an account? <a href="login.php">Sign in here</a></p>
        </div>

        <!-- Popup Modal -->
        <div id="popup" class="popup">
            <div class="popup-content">
                <p id="popup-message"></p>
                <button onclick="closePopup()">OK</button>
            </div>
        </div>
    </div>

    <style>
        .popup {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .popup-content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            width: 80%;
            max-width: 300px;
        }

        .popup-content p {
            margin-bottom: 20px;
        }
    </style>

    <script>
        // Declare isSuccess globally
        let isSuccess = false;

        document.addEventListener("DOMContentLoaded", function () {
            <?php if (isset($_SESSION['message'])): ?>
                // Display the popup with the session message
                document.getElementById("popup-message").textContent = "<?php echo $_SESSION['message']; ?>";
                document.getElementById("popup").style.display = "flex";

                // Set isSuccess based on the message type
                isSuccess = <?php echo $_SESSION['msg_type'] === 'success' ? 'true' : 'false'; ?>;

                <?php
                // Clear session message and type after loading
                unset($_SESSION['message']);
                unset($_SESSION['msg_type']);
                ?>
            <?php endif; ?>
        });

        function closePopup() {
            document.getElementById("popup").style.display = "none";

            // Redirect if the registration was successful
            if (isSuccess) {
                window.location.href = "login.php";
            }
        }
    </script>
</body>

</html>
