<?php
require '../navbar/nav.php';
require '../../connection.php';

// Fetch the top four mechanics based on the number of jobs done
$query = "
    SELECT mech_id, COUNT(*) AS job_count, name
    FROM vehicleissuesdone
    JOIN mechanic ON vehicleissuesdone.mech_id = mechanic.userID
    WHERE job_done_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY mech_id
    ORDER BY job_count DESC
    LIMIT 4
";

$result = mysqli_query($conn, $query);

$top_mechanics = [];
while ($row = mysqli_fetch_assoc($result)) {
    $top_mechanics[] = $row;
}
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

           <!--<a href="../home/home.php" class="button modify">Services</a>-->
        </div>

        <div class="contribution">
            <div class="title-contri">
                <h1>Best Mechanics of this week</h1>
            </div>
            <div class="body-contri">
                <div class="carousel">
                    <button class="carousel-btn prev">&#10094;</button>
                    <div class="carousel-items">
                        <?php foreach ($top_mechanics as $mechanic): ?>
                            <div class="carousel-item">
                                <h3><?php echo htmlspecialchars($mechanic['name']); ?></h3>
                                <img src="../../img/mechanic.png" alt="best mechanics" class="img-best-mechanic">
                                <p><?php echo htmlspecialchars($mechanic['name']); ?> has completed <?php echo $mechanic['job_count']; ?> jobs this week, providing excellent repair services and customer satisfaction.</p>
                            </div>
                        <?php endforeach; ?>
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
