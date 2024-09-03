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
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <img src="../../img/ss.png" alt="Drive Saviour Logo">
            </div>
            <nav>
                <ul>
                    <li class="dropdown">
                        <a href="#" class="dropbtn"><i class="fas fa-user"></i> Profile</a>
                        <div class="dropdown-content">
                            <a href="#"><i class="fas fa-edit"></i> Edit Profile</a>
                            <a href="#"><i class="fas fa-key"></i> Change Password</a>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropbtn"><i class="fas fa-store"></i> Shop</a>
                        <div class="dropdown-content">
                            <a href="#"><i class="fas fa-edit"></i> Edit Shop</a>
                            <a href="#"><i class="fas fa-boxes"></i> Manage Products</a>
                            <a href="#"><i class="fas fa-receipt"></i> Manage Orders</a>
                            <a href="#"><i class="fas fa-star"></i> Customer Ratings & Reviews</a>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropbtn"><i class="fas fa-warehouse"></i> Garage</a>
                        <div class="dropdown-content">
                            <a href="#"><i class="fas fa-tools"></i> Edit Garage</a>
                            <a href="#"><i class="fas fa-calendar-check"></i> View Bookings</a>
                            <a href="#"><i class="fas fa-plus-circle"></i> Add Services</a>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="bottom-links">
                <a href="#" class="bottom-link"><i class="fas fa-headset"></i> Contact Admin</a>
                <a href="#" class="bottom-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        
        <div class="main-content">
            <div class="home-container">
                <div class="overview-cards">
                    <div class="card">
                        <i class="fas fa-store"></i>
                        <h3>Shop</h3>
                    </div>
                    <div class="card">
                        <i class="fas fa-car"></i>
                        <h3>15</h3>
                        <p>Cars in Garage</p>
                    </div>
                    <div class="card">
                        <i class="fas fa-chart-line"></i>
                        <h3>250+</h3>
                        <p>Today Sales</p>
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
                        <p>Mobil Super</p>
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
