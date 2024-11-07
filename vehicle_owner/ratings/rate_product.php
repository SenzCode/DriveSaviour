<?php
session_start();
require '../../connection.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$userEmail = $_SESSION['email'];
$product_id = $_GET['product_id'] ?? 0;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];

    $query = "INSERT INTO ratings (product_id, user_email, rating, feedback) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isis", $product_id, $userEmail, $rating, $feedback);
    $stmt->execute();

    echo "Thank you for your feedback!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Rate Product</title>
</head>
<body>
    <h2>Rate this Product</h2>
    <form method="POST">
        <label for="rating">Rating (1 to 5):</label>
        <select name="rating" id="rating" required>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
        </select>
        
        <label for="feedback">Feedback:</label>
        <textarea name="feedback" id="feedback" rows="4" required></textarea>
        
        <button type="submit">Submit Rating</button>
    </form>
</body>
</html>
