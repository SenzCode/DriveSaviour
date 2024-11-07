<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../Login/Login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveSaviour</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container">
        <!-- Toggle Button -->
        <div class="menu-toggle" id="menu-toggle">
            <i class='bx bx-menu'></i>
        </div>
        <nav id="sidebar">
            <ul>
                <li>
                    <a href="../home/home.php" class="logo">
                        <img src="../../img/ss.png" alt="Logo">
                    </a>
                </li>
                <li class="dropdown">
                    <a href="../home/home.php" class="dropbtn">
                        <i class='bx bxs-dashboard'></i>
                        <span class="nav-item">Dashboard</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">
                        <i class='bx bx-add-to-queue'></i>
                        <span class="nav-item">Profile</span>
                    </a>
                    <div class="dropdown-content">
                        <a href="/shop_owner/ownerprofile/manage_profile.php"><i class="fas fa-edit"></i> Edit Profile</a>
                        <a href="/shop_owner/password/manage_password.php"><i class="fas fa-key"></i> Change Password</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">
                        <i class='bx bx-list-ul'></i>
                        <span class="nav-item">Shop</span>
                    </a>
                    <div class="dropdown-content">
                        <a href="../shop/shop.php"><i class="fas fa-edit"></i> Manage Shop</a>
                        <a href="../shop/shoplist.php"><i class="fas fa-boxes"></i> Manage Products</a>
                        <a href="../order/orders.php"><i class="fas fa-receipt"></i> Manage Orders</a>
                        <a href="../Income/income.php"><i class="fas fa-coins"></i> Income Dashboard</a>
                        <a href="../ratings/ratings.php"><i class="fas fa-star"></i> Customer Ratings & Reviews</a>
                    </div>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropbtn">
                        <i class='bx bx-show'></i>
                        <span class="nav-item">Garage</span>
                    </a>
                    <div class="dropdown-content">
                        <a href="#"><i class="fas fa-tools"></i> Edit Garage</a>
                        <a href="#"><i class="fas fa-calendar-check"></i> View Bookings</a>
                        <a href="#"><i class="fas fa-plus-circle"></i> Add Services</a>
                    </div>
                </li>
                <li>
                    <form method="POST">
                        <button class="logout" name="logout">
                            <i class='bx bx-log-out-circle'></i>
                            <span class="footer-item">Logout</span>
                        </button>
                    </form>
                    <button class="Contact" name="Contact">
                        <a href="../contact/contact.php">
                            <i class='bx bx-support'></i>
                            <span class="footer-item">Contact Admin</span>
                        </a>
                    </button>
                </li>
            </ul>
        </nav>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    document.querySelectorAll('.dropdown').forEach(dropdown => {
        dropdown.querySelector('.dropbtn').addEventListener('click', () => {
            // Close any currently open dropdowns
            document.querySelectorAll('.dropdown-content').forEach(content => {
                if (content !== dropdown.querySelector('.dropdown-content')) {
                    content.classList.remove('show');
                }
            });

            // Toggle the clicked dropdown
            dropdown.querySelector('.dropdown-content').classList.toggle('show');
        });
    });
});

    </script>
</body>
</html>
