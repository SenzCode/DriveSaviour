<?php
session_start();

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$loggedInOwnerEmail = $_SESSION['email'];

include_once('../../connection.php');
require('../navbar/nav.php');

// Retrieve product IDs for shops owned by the logged-in user
$productIds = [];
$stmt = $conn->prepare("
    SELECT products.id 
    FROM products
    JOIN shops ON products.shop_id = shops.id
    JOIN shop_owner ON shops.ownerEmail = shop_owner.email
    WHERE shop_owner.email = ?");
$stmt->bind_param("s", $loggedInOwnerEmail);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $productIds[] = $row['id'];
}
$stmt->close();

// Retrieve ratings for user's products
$ratings_data = [];
if (!empty($productIds)) {
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $stmt = $conn->prepare("
        SELECT ratings.product_id, ratings.rating, ratings.feedback, ratings.rating_date
        FROM ratings
        WHERE ratings.product_id IN ($placeholders)");
    $stmt->bind_param(str_repeat("i", count($productIds)), ...$productIds);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $ratings_data[] = $row;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Ratings</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>
    <div class="main_container">
        <h1>Product Ratings</h1>

        <?php if (count($ratings_data) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Rating</th>
                        <th>Feedback</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ratings_data as $rating): ?>
                        <tr>
                            <td><?= htmlspecialchars($rating['product_id']) ?></td>
                            <td><?= htmlspecialchars($rating['rating']) ?></td>
                            <td><?= htmlspecialchars($rating['feedback']) ?></td>
                            <td><?= htmlspecialchars($rating['rating_date']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No ratings found for your products.</p>
        <?php endif; ?>
    </div>
</body>
</html>
