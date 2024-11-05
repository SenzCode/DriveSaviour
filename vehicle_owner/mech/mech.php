<?php
    require '../navbar/nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>
    <div class="container-mech">
        <div class="top">
            <div class="topic">
                <h1 class="first">Expert Car Repair Services,</h1>
                <h1>Wherever You are?</h1>
                <h1>We're Here to Help</h1>


            <a href="../post/post.php" class="button">Find</a>
            <a href="breakdown_details.php" class="button">Breakdowns</a>

            </div>
            <div class="side-img">
            <img src="../../img/mechnic.png" alt="mechanic image">
            </div>
        </div>

        <div class="services">
            <div class="box-service">
                <img src="../../img/mechanic.png" alt="mechanic image" class="image-services">
                <p>We offer a wide range of auto repair services. Our expert mechanics diagnose issues quickly and find the safest, most cost-effective solution to get your vehicle back on the road.</p>
            </div>
            <div class="box-service">
                <img src="../../img/handshake.png" alt="handshake image" class="image-services">
                <p>The Drive Saviour peace-of-mind warranty means you’re protected against any unexpected issues. We cover parts and labor for a full year, ensuring that you’re never stuck with a problem we can fix.</p>
            </div>
            <div class="box-service">
                <img src="../../img/satisfaction.png" alt="5 star rating image" class="image-services">
                <p>Our 5-star service at Drive Saviour is built on trust and reliability. We back our work with a strong warranty, so you can drive with confidence, knowing you’re fully covered.</p>
            </div>
            <div class="box-service">
                <img src="../../img/user-experience.png" alt="user-experience image" class="image-services">
                <p>With up to 5 years of hands-on experience, our mechanics at Drive Saviour deliver expert care for your vehicle. Their skill and dedication ensure a quick and safe return to the road.</p>
            </div>
        </div>

        <div class="view-services">
            <h2>Explore our full range of expert <br>
            Auto repair and maintenance services.</h2>

            <a href="../home/home.php" class="button modify">Services</a>
        </div>

        <div class="contribution">
            <div class="title-contri">
                <h1>Best Mechanics of this week</h1>
            </div>
            <div class="body-contri">
                <div class="carousel">
                    <button class="carousel-btn prev">&#10094;</button>
                    <div class="carousel-items">
                        <div class="carousel-item">
                            <h3>Franklin Rodriguez</h3>
                            <img src="../../img/mechanic.png" alt="best mechanincs" class="img-best-mechanic">
                            <p>Frank is a master mechanic with over 30 years of experience. Known for his precision and expertise in engine rebuilding, Frank's workshop is the go-to place for classic car enthusiasts.</p>
                        </div>
                        <div class="carousel-item">
                            <h3>Migual Sanchez</h3>
                            <img src="../../img/mechanic.png" alt="best mechanincs" class="img-best-mechanic">
                            <p>With a passion for performance tuning, Mike specializes in high-performance modifications. His knowledge of turbochargers and custom exhaust systems has made him a favorite among speed lovers.</p>
                        </div>
                        <div class="carousel-item">
                            <h3>Vincent Romano</h3>
                            <img src="../../img/mechanic.png" alt="best mechanincs" class="img-best-mechanic">
                            <p>Vince brings a touch of old-school craftsmanship to the modern auto repair world. His meticulous attention to detail and commitment to quality work are evident in every car he services.</p>
                        </div>
                        <div class="carousel-item">
                            <h3>Sophia Davis</h3>
                            <img src="../../img/mechanic.png" alt="best mechanincs" class="img-best-mechanic">
                            <p>Sophia is a rising star in the automotive repair industry. With a knack for diagnostics and electrical systems, she’s quickly earned a reputation for solving the toughest car problems.</p>
                        </div>
                    </div>
                    <button class="carousel-btn next">&#10095;</button>
                </div>
            </div>
        </div>
    </div> <br> <br>

    <?php
    require '../footer/footer.php';
    ?>


    <script src="script.js"></script>
</body>
</html>
