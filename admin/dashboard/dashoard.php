<?php
ob_start(); 

require '../navbar/navbar.php';
require '../../connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../adminLogin/adminLogin.php");
    exit();
} 

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit();
}

$loggedInOwnerEmail = $_SESSION['email'];

try {
    $stmt = $conn->prepare("SELECT p.*, s.* 
                            FROM products p 
                            JOIN shops s ON p.shop_id = s.id 
                            WHERE p.quantity_available <= 5 
                            AND s.ownerEmail = ?");
    $stmt->bind_param("s", $loggedInOwnerEmail);

    $stmt->execute();
    $result = $stmt->get_result();

    $product_data = [];

    // Fetch all products that need to be restocked
    while ($row = $result->fetch_assoc()) {
        $product_data[] = $row;
    }
    $stmt->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Cafe</title>
    <link rel="shortcut icon" type="image/png" href="/img/Latte-Cappuccino-Transparent-PNG.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="../navbar/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <div class="profile">
        <div class="image">
            <h2>Welcome to Admin Dashboard</h2>
        </div>
    </div>

    <div class="wrapper">
        <div class="container1">
            <img src="../../img/mechanic.png">
            <span class="num plus-sign">100</span>
            <span class="text">Total Mechanics</span>
        </div>

        <div class="container1">
            <img src="../../img/team.png">
            <span class="num plus-sign">500+</span>
            <span class="text">Total Users</span>
        </div>

        <div class="container1">
            <img src="../../img/checklist.png">
            <span class="num">200+</span>
            <span class="text">Total Shop Items</span>
        </div>
    </div>

    <div class="content">
        <div class="text">
            <h2>Goods to be restocked <i class='bx bxs-bell-ring'></i> </h2>

        </div>

        <div class="wrappers">
            <?php if(!empty($product_data)): ?>
                <?php foreach($product_data as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['product_name']; ?>">
                        <div class="product-name"><?php echo $product['product_name']; ?></div>
                        <div class="product-quantity">Available: <?php echo $product['quantity_available']; ?></div>
                        <div class="shop-name">Shop: <?php echo $product['shop_name']; ?> <?php echo $product['branch']; ?> Branch</div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products need restocking at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    </script>    
</body>
</html>
