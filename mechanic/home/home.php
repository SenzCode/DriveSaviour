<?php
    require '../navbar/nav.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" type="dp" href="img/ss.png">
</head>
<body>
   
<section id="home">
    <div class="container-home-section">
        <div class="home-content reveal">
            <h1>Welcome,</h1>
            <h3>to <span></span></h3>
            <p>Our web app connects users with local mechanics for vehicle breakdowns, allowing easy posting, browsing, and towing services. It features customer reviews, live chat, and secure payments, making vehicle maintenance convenient and stress-free.</p>
        </div>
        <div class="profile-picture reveal">
            <!-- Background image -->
            <img class="side-img" src="img/Leonardo_Phoenix_A_mechanic_in_a_modern_garage_wearing_a_dark_1.png" alt="Mechanic">
            <!-- Foreground images -->
            <img class="gear rotate1" src="img/settings-icon.png" alt="Gear 1">
            <img class="gear rotate2" src="img/settings-icon.png" alt="Gear 2">
            <img class="gear rotate3" src="img/settings-icon.png" alt="Gear 3">
        </div>
    </div>
</section>

<!--services-->
<section id="services">
        <div class="text-center">
          <h1 class="services-text reveal">Key Features</h1>
        </div>
        <div class="container">
            <div class="services-boxes">
                <div class="service-box reveal">
                    <img src="../../img/pngwing.com (8).png">
                    <h3>On-Demand Mechanics</h3>
                </div>
                <div class="service-box reveal">
                    <img src="../../img/towinig.png">
                    <h3>Emergency Towing</h3>
                </div>
                <div class="service-box reveal">
                    <img src="../../img/oils.png">
                    <h3>Shop & Compare</h3>
                </div>
                <div class="service-box reveal">
                    <img src="../../img/card.png">
                    <h3>Secure Payments</h3>
                </div>
            </div>
        </div>
    </section> <br> 


    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="js/script.js"></script>


    <script>
    document.getElementById("menu-toggle").addEventListener("click", function() {
    var menu = document.getElementById("menu");
    var toggleButton = document.getElementById("menu-toggle");
    menu.classList.toggle("show");
    toggleButton.classList.toggle("show");
});


document.addEventListener('DOMContentLoaded', function () {
    const darkModeToggle = document.getElementById('darkmode-toggle');
    const body = document.body;

    darkModeToggle.addEventListener('change', function () {
        if (darkModeToggle.checked) {
            body.classList.add('dark-mode');
        } else {
            body.classList.remove('dark-mode');
        }
    });

    // Set initial state based on saved preference
    if (localStorage.getItem('darkMode') === 'enabled') {
        darkModeToggle.checked = true;
        body.classList.add('dark-mode');
    }

    darkModeToggle.addEventListener('change', function () {
        if (darkModeToggle.checked) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    });
});
    </script>
    <?php
    require '../../vehicle_owner/footer/footer.php';
?>
</body>
</html>