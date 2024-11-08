<?php
    require '../navbar/nav.php';
    require '../../connection.php';
    
    if (isset($_SESSION['email'])) {
        $loggedInEmail = $_SESSION['email'];
    } else {
        // If user is not logged in, redirect them to the login page
        header("Location: login.php");
        exit();
    }

    // Fetch shop data from the shops table where ownerEmail matches logged-in user's email
    $sql = "SELECT * FROM shops WHERE ownerEmail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $loggedInEmail);
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop List</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <style>
.shop-container {
    padding: 20px;
    text-align: center;
    margin-left: 300px; 
}

.shop-container h1 {
    margin-top: 80px; 
}

.shop-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); 
    gap: 20px;
    margin: 20px auto;
}

.shop-card {
    background-color: #e0e0e0;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.shop-card:hover {
    transform: translateY(-10px);
}

.shop-card h2 {
    font-size: 24px;
    color: #333;
}

.shop-card p {
    font-size: 16px;
    color: #555;
    margin: 10px 0;
}

.view-products-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.view-products-btn:hover {
    background-color: #0056b3;
}

@media screen and (max-width: 1199px) {
    .shop-container {
        margin-left: 0;
        padding: 20px;
    }

    .shop-grid {
        grid-template-columns: repeat(2, 1fr); 
    }

    .shop-card {
        padding: 15px; 
    }

    .view-products-btn {
        padding: 8px 16px;
    }
}

@media screen and (max-width: 650px) {
    .shop-container {
        margin-left: 0;
        padding: 15px; 
    }

    .shop-grid {
        grid-template-columns: 1fr;
    }

    .shop-card {
        padding: 10px; 
    }

    .view-products-btn {
        width: 50%; 
        padding: 8px 16px; 
    }
}

    </style>
</head>
<body>
    <!-- Content Section -->
    <div class="shop-container">
        <h1>Shops</h1>
        <div class="shop-grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Dynamic shop data
                    echo "
                    <div class='shop-card'>
                        <h2>{$row['shop_name']}</h2>
                        <p>Branch: {$row['branch']}</p>
                        <p>Address: {$row['address']}</p>
                        <p>Phone: {$row['number']}</p>
                        <!-- Hardcoded Ratings and Reviews -->
                        <p><strong>Ratings:</strong> 4.5 ★★★★☆ (120 reviews)</p>
                        <button class='view-products-btn' onclick='viewProducts({$row['id']})'>View Products</button>
                    </div>
                    ";
                }
            } else {
                echo "<p>No shops available.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        function viewProducts(shopId) {
            // Redirect to products.php with shop ID as query parameter
            window.location.href = "products.php?shop_id=" + shopId;
        }
    </script>
</body>
</html>
