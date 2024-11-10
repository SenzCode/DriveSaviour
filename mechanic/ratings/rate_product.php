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
    $rating = $_POST['rating'] ?? null;
    $feedback = $_POST['feedback'];
    $rating_id = $_POST['rating_id'] ?? null;

    if (!$rating) {
        echo "<script>alert('Please select at least one star for the rating.');</script>";
    } else {
        if ($rating_id) {
            // Update an existing rating
            $query = "UPDATE mech_ratings SET rating = ?, feedback = ? WHERE id = ? AND user_email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isis", $rating, $feedback, $rating_id, $userEmail);
            $stmt->execute();
            echo "Rating updated successfully!";
        } else {
            // Insert a new rating
            $query = "INSERT INTO mech_ratings (product_id, user_email, rating, feedback, rating_date) VALUES (?, ?, ?, ?, CURDATE())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isis", $product_id, $userEmail, $rating, $feedback);
            $stmt->execute();
            echo "Thank you for your feedback!";
        }
    }
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $query = "DELETE FROM mech_ratings WHERE id = ? AND user_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $delete_id, $userEmail);
    $stmt->execute();
    echo "Rating deleted successfully!";
}

// Retrieve existing ratings for the same product and user
$query = "SELECT id, rating, feedback, rating_date FROM mech_ratings WHERE product_id = ? AND user_email = ?";
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
    <style>
        /* Star rating styles */
        .stars {
            display: flex;
            direction: row-reverse;
            justify-content: center;
            font-size: 2rem;
            cursor: pointer;
        }
        .stars label {
            color: gray;
        }
        .stars input[type="radio"] {
            display: none;
        }
        .stars label:hover,
        .stars label:hover ~ label,
        .stars input:checked ~ label {
            color: gold;
        }

        /* Style for the star rating */
        .star {
            font-size: 1.5rem;
            color: lightgray;
        }

        .star.filled {
            color: gold;
        }
    </style>
</head>
<body>
    <div class="rate-body">
        <h2>Rate this Product</h2>
        <form method="POST" onsubmit="return validateForm()">
            <input type="hidden" name="rating_id" id="rating_id">
            
            <label for="rating">Rating:</label>
            <div class="stars">
                <!-- Star radio buttons -->
                <input type="radio" id="star5" name="rating" value="5"><label for="star5">&#9733;</label>
                <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
                <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
                <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
                <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
            </div>

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
                <td>
                    <!-- Display stars based on rating value -->
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star<?= $i <= $row['rating'] ? ' filled' : '' ?>">&starf;</span>
                    <?php endfor; ?>
                </td>
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
                document.getElementById('feedback').value = feedback;
                
                // Set the star rating
                const stars = document.getElementsByName('rating');
                stars.forEach(star => {
                    star.checked = (star.value == rating);
                });
            }

            // Function to validate the form
            function validateForm() {
                const ratingSelected = document.querySelector('input[name="rating"]:checked');
                if (!ratingSelected) {
                    alert("Please select at least one star for the rating.");
                    return false;
                }
                return true;
            }
        </script>
    </div>
</body>
</html>
