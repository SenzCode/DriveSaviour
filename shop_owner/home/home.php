<?php
    session_start();
    require '../navbar/nav.php';
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
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../navbar/style.css">

    <style>
        .product-card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            width: 250px;
            display: inline-block;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .product-card img {
            width: 150px;
            height: 150px;
            object-fit: contain;
        }
        .product-card .product-name {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .product-card .product-quantity, .product-card .shop-name {
            margin: 5px 0;
        }
        .wrappers {
            display: flex;
            flex-wrap: wrap;
            
        }
    </style>

</head>
<body>
        <div class="main-content">
            <div class="home-container">
                <div class="overview-cards">
                    <div class="card">
                        <i class="fas fa-store"></i>
                        <h2>50+</h2>
                        <h3>Shop</h3>
                    </div>
                    <div class="card">
                        <i class="fas fa-car"></i>
                        <h2>15+</h2>
                        <h3>Cars in Garage</h3>
                    </div>
                    <div class="card">
                        <i class="fas fa-chart-line"></i>
                        <h2>250+</h2>
                        <h3>Today Sales</h3>
                    </div>
                </div>

                <div class="content">
                    <div class="text">
                        <h2>Goods to be restocked <i class='bx bxs-bell-ring'></i></h2>

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
                
            </div>
        </div>
    </div>
</body>
</html>
