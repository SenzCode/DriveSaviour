<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveSaviour - About Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
    <div class="sidebar">
        <div class="logo">
            <img src="../../img/ss.png" alt="Logo">
        </div>
        <nav>
            <ul>
                <li class="dropdown">
                    <a href="#" class="dropbtn">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <div class="dropdown-content">
                        <a href="/shop_owner/ownerprofile/manage_profile.php"><i class="fas fa-edit"></i> Edit Profile</a>
                        <a href="/shop_owner/password/manage_password.php"><i class="fas fa-key"></i> Change Password</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">
                        <i class="fas fa-store"></i> Shop
                    </a>
                    <div class="dropdown-content">
                        <a href="../shop/shop.php"><i class="fas fa-edit"></i> Manage Shop</a>
                        <a href="#"><i class="fas fa-boxes"></i> Manage Products</a>
                        <a href="#"><i class="fas fa-receipt"></i> Manage Orders</a>
                        <a href="#"><i class="fas fa-coins"></i> Income Dashboard</a>
                        <a href="#"><i class="fas fa-star"></i> Customer Ratings & Reviews</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">
                        <i class="fas fa-warehouse"></i> Garage
                    </a>
                    <div class="dropdown-content">
                        <a href="#"><i class="fas fa-tools"></i> Edit Garage</a>
                        <a href="#"><i class="fas fa-calendar-check"></i> View Bookings</a>
                        <a href="#"><i class="fas fa-plus-circle"></i> Add Services</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div class="bottom-links">
            <a href="#" class="bottom-link">
                <i class="fas fa-headset"></i> Contact Admin
            </a>
            <a href="#" class="bottom-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }
    </script>
</body>
</html>
