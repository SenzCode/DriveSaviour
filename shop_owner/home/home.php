<?php
    require '../navbar/nav.php'
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

                <h2 class="section-title">Out of Stock</h2>
                <div class="product-grid">
                    <div class="product-card">
                        <img src="products/1.png" alt="AMARON BATTERY">
                        <p>AMARON BATTERY</p>
                    </div>
                    <div class="product-card">
                        <img src="products/2.png" alt="Mobil Super">
                        <p>MOBIL SUPER</p>
                    </div>
                    <div class="product-card">
                        <img src="products/7.png" alt="EXIDE BATTERY">
                        <p>EXIDE BATTERY</p>
                    </div>
                    <div class="product-card">
                        <img src="products/5.png" alt="CASTROL GTX">
                        <p>CASTROL GTX</p>
                    </div>
                    <div class="product-card">
                        <img src="products/3.png" alt="MOBILE SUPER">
                        <p>MOBILE SUPER</p>
                    </div>
                    <div class="product-card">
                        <img src="products/4.png" alt="EXIDE BATTERY">
                        <p>EXIDE BATTERY</p>
                        </div>
                    <div class="product-card">
                        <img src="products/6.png" alt="TEXAMATIC Oil">
                        <p>TEXAMATIC Oil</p>
                    
                    <!-- Add more product cards as needed -->
                </div>
            </div>
        </div>
    </div>
</body>
</html>
