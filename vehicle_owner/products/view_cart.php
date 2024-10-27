<?php
session_start(); // Ensure session is started at the top

require '../../connection.php';

// Check if user is logged in
if (!isset($_SESSION['userID'])) {
    header("Location: ../Login/login.php"); // Redirect to login if not logged in
    exit();
}

// Use the session variable for userID
$userID = $_SESSION['userID'];

// Fetch cart items for the logged-in user
$stmt = $conn->prepare("SELECT c.id, c.product_name, c.quantity, c.price, c.image_url 
                         FROM CartTable c 
                         WHERE c.username = (SELECT email FROM vehicle_owner WHERE id = ?)");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Your Cart</title>
</head>
<body>

    <h1>Your Shopping Cart</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['price']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Product Image" width="100"></td>
                    <td>
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="cart_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <a href="checkout.php">Proceed to Checkout</a>
    
    <br>
    <a href="logout.php">Logout</a>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
