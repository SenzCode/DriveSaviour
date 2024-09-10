<?php
session_start();
 
// Ensure no output before this line
require '../navbar/nav.php'; 
require '../../connection.php';
 
error_reporting(E_ALL);
ini_set('display_errors', 1);
 
// Retrieve user email from session or other source
if (!isset($_SESSION['email'])) {
    // Redirect if the user is not logged in
    header("Location: ../Login/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}
 
$user_email = $_SESSION['email'];
$query = $conn->prepare("SELECT * FROM shop_owner WHERE email = ?");
$query->bind_param("s", $user_email);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();
 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    // Sanitize and validate input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $dob = htmlspecialchars(trim($_POST['dob']));
 
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } else {
        // Update profile based on email
        $update_query = $conn->prepare("UPDATE shop_owner SET name = ?, email = ?, phone = ?, dob = ? WHERE email = ?");
        $update_query->bind_param("sssss", $name, $email, $phone, $dob, $user_email);
 
        if ($update_query->execute()) {
            // Update the session email if it changes
            $_SESSION['user_email'] = $email;
            $message = "Profile updated successfully!";
        } else {
            $message = "Error updating profile: " . $update_query->error;
        }
 
        $update_query->close();
    }
}
 
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Profile</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="styles.css"> 
<link rel="stylesheet" href="../navbar/style.css">
</head>
<body>
<div class="main-content">
<h1>Manage Profile</h1>
 
        <?php if (isset($message)): ?>
<p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
 
        <form action="manage_profile.php" method="POST">
<div>
<label for="name">Name:</label>
<input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
</div>
<div>
<label for="email">Email:</label>
<input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
</div>
<div>
<label for="phone">Phone:</label>
<input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
</div>
<div>
<label for="dob">Date of Birth:</label>
<input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>
</div>
<button type="submit" name="update_profile">Update Profile</button>
</form>
</div>
</body>
</html>