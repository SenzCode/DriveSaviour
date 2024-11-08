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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../navbar/style.css"> <!-- Include the updated style.css -->
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <a href="../home/home.php" class="logo">
                    <img src="../../img/1s.png" alt="Logo">
                </a>
            </div>
            <ul class="list-unstyled components">
                <li>
                    <a href="../home/home.php">
                        <i class="bx bxs-dashboard"></i>
                        <span class="nav-item">Dashboard</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#">
                        <i class='bx bx-user'></i>
                        <span class="nav-item">Profile</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="/shop_owner/ownerprofile/manage_profile.php"><i class="fas fa-edit"></i> Edit Profile</a></li>
                        <li><a href="/shop_owner/password/manage_password.php"><i class="fas fa-key"></i> Change Password</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#">
                        <i class='bx bx-store'></i>
                        <span class="nav-item">Shop</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="../shop/shop.php"><i class="fas fa-edit"></i> Manage Shop</a></li>
                        <li><a href="../shop/shoplist.php"><i class="fas fa-boxes"></i> Manage Products</a></li>
                        <li><a href="../order/orders.php"><i class="fas fa-receipt"></i> Manage Orders</a></li>
                        <li><a href="../Income/income.php"><i class="fas fa-coins"></i> Income Dashboard</a></li>
                        <li><a href="../ratings/ratings.php"><i class="fas fa-star"></i> Customer Ratings</a></li>
                    </ul>

                </li>
            </ul>

            <div class="sidebar-footer">
                <ul class="list-unstyled components">
                    <li>
                        <a href="../contact/contact.php">
                            <i class="bx bx-support"></i>
                            <span class="footer-item">Contact Admin</span>
                        </a>
                    </li>

                    <li>
                        <a href="../Login/login.php">
                            <i class="bx bx-log-out-circle"></i>
                            <span class="footer-item">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="menu-toggle" id="menu-toggle">
            <i class="bx bx-menu" id="menu-icon"></i> 
            <i class="fas fa-times" id="close-icon" style="display: none;"></i> 
        </div>

    </div>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const menuIcon = document.getElementById('menu-icon');
    const closeIcon = document.getElementById('close-icon');

    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');

        if (sidebar.classList.contains('active')) {
            menuIcon.style.display = 'none';  
            closeIcon.style.display = 'block';  
        } else {
            menuIcon.style.display = 'block';  
            closeIcon.style.display = 'none';  
        }
    });

    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(dropdown => {
        const dropdownMenu = dropdown.querySelector('.dropdown-menu');

        dropdown.addEventListener('mouseenter', () => {
            dropdowns.forEach(d => {
                if (d !== dropdown) {
                    d.querySelector('.dropdown-menu').classList.remove('show');
                }
            });
            dropdownMenu.classList.add('show');
        });

        dropdown.addEventListener('mouseleave', () => {
            dropdownMenu.classList.remove('show');
        });
    });
});

    </script>
</body>
</html>