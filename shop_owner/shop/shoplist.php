<?php
    require '../navbar/nav.php';
    require '../../connection.php';
    
    if (isset($_SESSION['email'])) {
        $loggedInEmail = $_SESSION['email'];
    } else {
        header("Location: login.php");
        exit();
    }

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
    font-family: 'Arial', sans-serif;
}

.shop-container h1 {
    margin-top: 80px;
    font-size: 36px;
    font-weight: bold;
    color: #3a3a3a;
    letter-spacing: 1px;
}

.shop-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin: 20px auto;
    max-width: 1200px;
}

.shop-card {
    background-color: #ffffff; /* Clean white background */
    border: 2px solid #2B5AC2; /* Border matches button background */
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.shop-card:hover {
    transform: translateY(-5px); /* Slight lift on hover */
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.shop-card h2 {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
}

.shop-card p {
    font-size: 16px;
    color: #666;
    margin: 8px 0;
}

.view-products-btn {
    display: inline-block;
    margin-bottom: 10px;
    margin-top: 20px;
    width: 150px;
    color: #182431;
    font-weight: bold;
    padding: 10px;
    border: 1px solid #2B5AC2;
    border-radius: 88px;
    background-color: #f0f8ff;
    text-decoration: none;
    text-align: center;
    transition: background-color .50s ease, color .50s ease;
}

.view-products-btn:hover {
    background-color: #2B5AC2;
    color: #ffffff;
    cursor: pointer;
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
        padding: 18px;
    }

    .view-products-btn {
        padding: 10px 18px;
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
        padding: 15px;
    }

    .view-products-btn {
        width: 100%; /* Full-width button for mobile */
        padding: 10px;
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
                        <!--<p><strong>Ratings:</strong> 4.5 ★★★★☆ (120 reviews)</p>-->
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
            window.location.href = "products.php?shop_id=" + shopId;
        }
    </script>
</body>
</html>
