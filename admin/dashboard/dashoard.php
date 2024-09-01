<?php
ob_start(); 

require '../navbar/navbar.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../adminLogin/adminLogin.php");
    exit();
} 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Gallery Cafe</title>
    <link rel="shortcut icon" type="image/png" href="/img/Latte-Cappuccino-Transparent-PNG.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="../navbar/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>
    <div class="profile">
        <div class="image">
            <h2>Welcome to Admin Dashboard</h2>
        </div>
    </div>

    <div class="wrapper">
        <div class="container1">
            <img src="../../img/mechanic.png">
            <span class="num plus-sign">100</span>
            <span class="text">Total Mechanics</span>
        </div>

        <div class="container1">
            <img src="../../img/team.png">
            <span class="num plus-sign">500+</span>
            <span class="text">Total Users</span>
        </div>

        <div class="container1">
            <img src="../../img/checklist.png">
            <span class="num">200+</span>
            <span class="text">Total Shop Items</span>
        </div>
    </div>

    <div class="content">
        <div class="text">
            <h2>Recent Booking</h2>
            <h3>No Booking</h3>
        </div>
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
