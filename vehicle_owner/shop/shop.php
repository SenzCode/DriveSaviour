<?php
    require '../navbar/nav.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>
    <div class="container-shops">
        <div class="topic">
             <h1>Shop</h1><br>

        </div>
        

        <div class="title">
            <div class="title-text">
                <p>Explore a wide network of registered automotive shops through our Shop page, where quality and convenience meet. Whether you’re looking for a trusted mechanic, specialized repair services, or parts and accessories, DriveSaviour connects you with top-rated shops in your area. Each shop profile provides detailed information, including services offered, customer reviews, and contact details, helping you make informed decisions about your vehicle’s care.</p>
                <a href="../home/home.php" class="button browse">Browse</a>
                <a href="modify/modify.php" class="button modify">Modify Car</a>
            </div>
            <div class="title-image">
                <img src="../../img/shop.png" alt="Mechanic and Car">
            </div>
        </div>

        <div class="carousel">
            <button class="carousel-btn prev">&#10094;</button>
            <div class="carousel-items">
                <div class="carousel-item">1</div>
                <div class="carousel-item">2</div>
                <div class="carousel-item">3</div>
                <div class="carousel-item">4</div>
                <div class="carousel-item">5</div>
                <div class="carousel-item">6</div>
            </div>
            <button class="carousel-btn next">&#10095;</button>
        </div>
    </div>

    <?php
    require '../footer/footer.php';
    ?>


    <script src="script.js"></script>
</body>
</html>

