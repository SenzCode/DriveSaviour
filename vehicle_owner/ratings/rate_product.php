<?php
session_start();
require '../../connection.php';
require '../navbar/nav.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$userEmail = $_SESSION['email'];
$product_id = $_GET['product_id'] ?? 0;

// Handle form submission for adding or updating a rating
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];
    $rating_id = $_POST['rating_id'] ?? null;

    if ($rating_id) {
        // Update an existing rating
        $query = "UPDATE ratings SET rating = ?, feedback = ? WHERE id = ? AND user_email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isis", $rating, $feedback, $rating_id, $userEmail);
        $stmt->execute();
        echo "Rating updated successfully!";
    } else {
        // Insert a new rating
        $query = "INSERT INTO ratings (product_id, user_email, rating, feedback) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isis", $product_id, $userEmail, $rating, $feedback);
        $stmt->execute();
        echo "Thank you for your feedback!";
    }
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM ratings WHERE id = ? AND user_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $delete_id, $userEmail);
    $stmt->execute();
    echo "Rating deleted successfully!";
}

// Retrieve existing ratings for the same product and user
$query = "SELECT id, rating, feedback FROM ratings WHERE product_id = ? AND user_email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $product_id, $userEmail);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="style.css">
    <title>Rate Product</title>
</head>
<body>
    <div class="rate-body">
            <h2>Rate this Product</h2>
            <form method="POST">
                <input type="hidden" name="rating_id" id="rating_id">
                <label for="rating">Rating (1 to 5):</label>
                <select name="rating" id="rating" required>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                
                <label for="feedback">Feedback:</label>
                <textarea name="feedback" id="feedback" rows="4" required></textarea>
                
                <button type="submit">Submit Rating</button>

                <button class="view-cart-btn" onclick="window.location.href='../orders/orders.php'">Orders</button>
            </form>

            <h3>Your Previous Ratings for This Product</h3>
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Rating</th>
                        <th>Feedback</th>
                        <th>Actions</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['rating']) ?></td>
                            <td><?= htmlspecialchars($row['feedback']) ?></td>
                            <td>
                                <button onclick="editRating(<?= $row['id'] ?>, <?= $row['rating'] ?>, '<?= addslashes($row['feedback']) ?>')">Edit</button>
                                <a href="?product_id=<?= $product_id ?>&delete_id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this rating?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No previous ratings found for this product.</p>
            <?php endif; ?>

        <script>
            // Function to populate form for editing
            function editRating(id, rating, feedback) {
                document.getElementById('rating_id').value = id;
                document.getElementById('rating').value = rating;
                document.getElementById('feedback').value = feedback;
            }
        </script>
    </div>
</body>
</html>
