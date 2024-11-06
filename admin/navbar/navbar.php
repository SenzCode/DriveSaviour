<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveSaviour</title>
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
                    <a href="../dashboard/dashoard.php" class="logo">
                        <img src="../../img/ss.png">
                    </a>
                </li>
                <li><a href="../dashboard/dashoard.php"><i class='bx bxs-dashboard'></i><span class="nav-item">Dashboard</span></a></li>
                <li><a href="../shop/shop.php"><i class='bx bxs-store'></i><span class="nav-item">Own Shops</span></a></li>
                <li><a href="../shop/view_shop.php"><i class='bx bx-list-ul'></i><span class="nav-item">View Shop</span></a></li>
                <li><a href="../mechanic/view_mechanic.php"><i class='bx bx-show'></i><span class="nav-item">View Mechanics</span></a></li>
                <li><a href="../Vehicle Owners/veiw_owners.php"><i class='bx bxs-file-find'></i><span class="nav-item">View Vehicle Owner</span></a></li>
                <li><a href="../categories/category.php"><i class='bx bxs-category-alt'></i><span class="nav-item">Product Catogories</span></a></li>
                <li><a href="../commission/commission.php"><i class='bx bx-money-withdraw'></i><span class="nav-item">Commission</span></a></li>
                <li><a href="../finance/finance.php"><i class='bx bx-money-withdraw'></i><span class="nav-item">Finance</span></a></li>
                <li><a href="../categories/category.php"><i class='bx bxl-telegram'></i><span class="nav-item">Seller Payments</span></a></li>
                <li>
                    <a href="../dashboard/dashoard.php">
                        <i class='bx bxs-dashboard'></i>
                        <span class="nav-item">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="../shop/shop.php">
                    <i class='bx bxs-store'></i>
                        <span class="nav-item">Own Shops</span>
                    </a>
                </li>
                <li>
                    <a href="../shop/view_shop.php">
                       <i class='bx bx-list-ul'></i>
                        <span class="nav-item">View Shop</span>
                    </a>
                </li>
                <li>
                    <a href="../mechanic/view_mechanic.php">
                        <i class='bx bx-show'></i>
                        <span class="nav-item">View Mechanics</span>
                    </a>
                </li>
                <li>
                    <a href="../Vehicle Owners/veiw_owners.php">
                        <i class='bx bxs-file-find'></i>
                        <span class="nav-item">View Vehicle Owner</span>
                    </a>
                </li>
                <li>
                    <a href="../categories/category.php">
                        <i class='bx bxs-category-alt'></i>
                        <span class="nav-item">Product Catogories</span>
                    </a>
                </li>
                <li>
                    <a href="../Finance/finance.php">
                        <i class='bx bx-money-withdraw'></i>
                        <span class="nav-item">Finance</span>
                    </a>
                </li>
                <li>
                    <a href="../commission/commission.php">
                        <i class='bx bx-money-withdraw'></i>
                        <span class="nav-item">Commission</span>
                    </a>
                </li>
                <li>
                    <a href="../categories/category.php">
                        <i class='bx bxl-telegram'></i>
                        <span class="nav-item">Seller Payments</span>
                    </a>
                </li>

                <li>
                    <form method="POST">
                        <button class="logout" name="logout">
                            <i class='bx bx-log-out-circle'></i>
                            <span class="footer-item">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
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
