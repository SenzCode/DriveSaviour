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
        <div class="menu-toggle" id="menu-toggle">
            <i class='bx bx-menu'></i>
        </div>
        
        <nav id="sidebar">
            <div class="close-sidebar" id="close-sidebar">
                <i class='bx bx-x'></i>
            </div>
            <ul>
                <li>
                    <a href="../dashboard/dashoard.php" class="logo">
                        <img src="../../img/ss.png" alt="Logo">
                    </a>
                </li>
                <li>
                    <form method="POST">
                        <button class="logout" name="logout">
                            <i class='bx bx-log-out-circle'></i><span class="footer-item">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        const closeSidebar = document.getElementById('close-sidebar');

        menuToggle.addEventListener('click', () => sidebar.classList.toggle('active'));
        closeSidebar.addEventListener('click', () => sidebar.classList.remove('active'));
    </script>
</body>
</html>
