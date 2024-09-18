<?php
    require '../navbar/nav.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container-shops">
        <div class="topic">
             <h1>Shop</h1>

        </div>
        

        <div class="title">
            <div class="title-text">
                <p>Welcome to the DriveSavior Shop Page, where mechanics can explore a range of essential tools, equipment, and resources designed 
                    to enhance your service capabilities. Here, you’ll find everything from diagnostic tools to repair kits, all curated to support
                     your daily operations and ensure you deliver top-notch repairs. Our shop features high-quality products from trusted brands, 
                     tailored to meet the needs of professionals in the automotive industry. Browse through our selection, and equip yourself with
                      the best tools to handle any job efficiently and effectively.</p>
                <button class="browse">
                    <a href="../home/home.php">Browse</a>
                </button>
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

    <script src="script.js"></script>

</body>
</html>

