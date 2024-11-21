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
    <link rel="stylesheet" href="modal.css">
    <link rel="shortcut icon" type="dp" href="img/ss.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <style>
    </style>
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
            <img class="side-img" src="img/Leonardo_Phoenix_A_mechanic_in_a_modern_garage_wearing_a_dark_1.png" alt="Mechanic" loading='lazy'>
            <img class="gear rotate1" src="img/settings-icon.png" alt="Gear 1" loading='lazy'>
            <img class="gear rotate2" src="img/settings-icon.png" alt="Gear 2" loading='lazy'>
            <img class="gear rotate3" src="img/settings-icon.png" alt="Gear 3" loading='lazy'>
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
                    <img src="../../img/pngwing.com (8).png" loading='lazy'>
                    <h3>On-Demand Mechanics</h3>
                </div>
                <div class="service-box reveal">
                    <img src="../../img/towinig.png" loading='lazy'>
                    <h3>Emergency Towing</h3>
                </div>
                <div class="service-box reveal">
                    <img src="../../img/oils.png" loading='lazy'>
                    <h3>Shop & Compare</h3>
                </div>
                <div class="service-box reveal">
                    <img src="../../img/card.png" loading='lazy'>
                    <h3>Secure Payments</h3>
                </div>
            </div>
        </div>
    </section> <br> 

    <section class="modal">
    <div class="dropdown-container">
        <h2>Choose Your Car Model:</h2>
        <select id="carSelect">
            <option value="../shop/modify/benz/scene.gltf">Benz</option>
            <option value="../shop/modify/BMW/scene.gltf">BMW</option>
            <option value="../shop/modify/toyota_prius/scene.gltf">Toyota Prius</option>
            <option value="../shop/modify/scene.gltf">Honda</option>
            <option value="../shop/modify/nissan_navara/scene.gltf">Nissan Navara</option>
            <option value="../shop/modify/suzuki_wagonr/scene.gltf">Suzuki Wagonr</option>
            <option value="../shop/modify/mitshubishi/scene.gltf">Mitshubishi</option>
            <option value="../shop/modify/nissan_van/scene.gltf">Nissan Caravan</option>
        </select>
    </div>

    <!-- 3D content -->
    <model-viewer id="carModel" src="../shop/modify/benz/scene.gltf" alt="A 3D model of a car" shadow-intensity="1" camera-controls auto-rotate ar>
    </model-viewer>

    <div class="color-boxes-container">
    <div class="color-boxes">
        <div class="text">
            <h2>Choose Your Car's New Look!</h2>
        </div>
        <div class="box-border">
            <div class="color-box" style="background-color: #ff0000;" data-color="#ff0000" data-material="body"></div>
            <div class="color-box" style="background-color: #ffffff;" data-color="#ffffff" data-material="body"></div>
            <div class="color-box" style="background-color: #4b4b4b;" data-color="#4b4b4b" data-material="body"></div>
            <div class="color-box" style="background-color: #000000;" data-color="#000000" data-material="body"></div>
            <div class="color-box" style="background-color: #006400;" data-color="#006400" data-material="body"></div>
            <div class="color-box" style="background-color: #00008b;" data-color="#00008b" data-material="body"></div>
            <div class="color-box" style="background-color: #d68400;" data-color="#d68400" data-material="body"></div>
        </div>
    </div>
</div>

    </section>
    

    <section class="features">
    <div class="container-shops">
        <div class="title">
            <div class="title-image">
                <img src="../../img/Front car-pana.png" alt="Mechanic and Car">
            </div>
            <div class="title-text">
                <h2>Why Choose Us?</h2>
                <div class="p-box"><p>24/7 Availability: We're always here when you need us.</p></div>
                <div class="p-box"><p>Trusted Mechanics: All our mechanics are verified and highly rated.</p></div>
                <div class="p-box"><p>Competitive Pricing: Get the best rates without any hidden fees.</p></div>
                <div class="p-box"><p>Customer Support: Friendly and responsive support to assist you.</p></div>
            </div>
        </div>
    </div>
</section>

<section class="reviews">
<div class="container swiper">
       <div class="text-center">
          <h1 class="services-text reveal">Reviews</h1>
        </div>
        <div class="slider-wrapper">
            <div class="card-list swiper-wrapper">
                <div class=" swiper-slide card-item">
                    <img src="../../img/img-1.jpg" alt="User Image" class="user-image" loading='lazy'>
                    <h2 class="user-name">Prashid Dilshan</h2>
                    <div class="rating">
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star'></i>
                    </div>
                    <p class="user-profession">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa, sit!</p>
                </div>
                <div class=" swiper-slide card-item">
                    <img src="../../img/img-2.jpg" alt="User Image" class="user-image" loading='lazy'>
                    <h2 class="user-name">Shamali Liyanage</h2>
                    <div class="rating">
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star'></i>
                    </div>
                    <p class="user-profession">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa, sit!</p>
                </div>
                <div class=" swiper-slide card-item">
                    <img src="../../img/img-3.jpg" alt="User Image" class="user-image" loading='lazy'>
                    <h2 class="user-name">Ramitha Heshan</h2>
                    <div class="rating">
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star-half' ></i>
                    </div>
                    <p class="user-profession">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa, sit!</p>
                </div>
                <div class=" swiper-slide card-item">
                    <img src="../../img/img-4.jpg" alt="User Image" class="user-image" loading='lazy'>
                    <h2 class="user-name">Nusrath</h2>
                    <div class="rating">
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star'></i>
                    </div>
                    <p class="user-profession">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa, sit!</p>
                </div>
                <div class=" swiper-slide card-item">
                    <img src="../../img/img-5.jpg" alt="User Image" class="user-image" loading='lazy'>
                    <h2 class="user-name">Kasun Buddika</h2>
                    <div class="rating">
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star-half' ></i>
                    </div>
                    <p class="user-profession">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa, sit!</p>
                </div>
                <div class=" swiper-slide card-item">
                    <img src="../../img/img-6.jpg" alt="User Image" class="user-image" loading='lazy'>
                    <h2 class="user-name">Brain</h2>
                    <div class="rating">
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star'></i>
                       <i class='bx bxs-star-half' ></i>
                    </div>
                    <p class="user-profession">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ipsa, sit!</p>
                </div>
            </div>

            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>
  <!-- Linking Swiper JS CSS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


    <?php
    require '../footer/footer.php';
    ?>



    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="js/script.js"></script>


    <script>
        const swiper = new Swiper('.slider-wrapper', {
    // Optional parameters
    loop: true,
    grabCursor: true,
    spaceBetween: 30,
  
    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
      dynamicBullets: true,
    },
  
    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  
     // Responssive breakpoint

  breakpoints: {
    0: {
        slidesPerView:1
    },
    620: {
        slidesPerview: 2
    },
    1024: {
        slidesPerView: 3
    }
      }

  });


 
    </script>


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
    });z

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
function showPreloader(event, url) {
        event.preventDefault(); // Prevent the default link behavior
        // Show the preloader
        const preloaderWrap = document.createElement('div');
        preloaderWrap.classList.add('preloader-wrap');
        preloaderWrap.innerHTML = `
            <div class="preloader">
                <div class="loading-circle loading-circle-one"></div>
                <div class="loading-circle loading-circle-two"></div>
                <div class="loading-circle loading-circle-three"></div>
            </div>
        `;
        document.body.appendChild(preloaderWrap);

        // Redirect after a short delay
        setTimeout(function() {
            window.location.href = url;
        }, 1300); // Adjust the time as needed
    }
    </script>

    
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script nomodule src="https://unpkg.com/@google/model-viewer/dist/model-viewer-legacy.js"></script>

    <script>
        const carModel = document.querySelector('#carModel');
        const colorBoxes = document.querySelectorAll('.color-box');
        const carSelect = document.querySelector('#carSelect');

        carSelect.addEventListener('change', (event) => {
            const selectedCar = event.target.value;
            carModel.src = selectedCar;
        });

        colorBoxes.forEach(box => {
    box.addEventListener('click', () => {
        const color = box.getAttribute('data-color');
        if (carModel.src.includes('BMW')) {
            carModel.model.materials[2].pbrMetallicRoughness.setBaseColorFactor(hexToRgb(color));
        } else if (carModel.src.includes('benz')) {
            carModel.model.materials[7].pbrMetallicRoughness.setBaseColorFactor(hexToRgb(color));
        } else if (carModel.src.includes('mitshubishi')) {
            carModel.model.materials[0].pbrMetallicRoughness.setBaseColorFactor(hexToRgb(color));
        }else if (carModel.src.includes('toyota_prius')) {
                carModel.model.materials[4].pbrMetallicRoughness.setBaseColorFactor(hexToRgb(color));
        }else if (carModel.src.includes('suzuki_wagonr')) {
                carModel.model.materials[3].pbrMetallicRoughness.setBaseColorFactor(hexToRgb(color));

        }else if (carModel.src.includes('nissan_van')) {
                carModel.model.materials[0].pbrMetallicRoughness.setBaseColorFactor(hexToRgb(color));
        }else if (carModel.src.includes('nissan_navara')) {
                carModel.model.materials[6].pbrMetallicRoughness.setBaseColorFactor(hexToRgb(color));

        } else {
            carModel.model.materials[1].pbrMetallicRoughness.setBaseColorFactor(hexToRgb(color));
        }
    });
});


        function hexToRgb(hex) {
            const bigint = parseInt(hex.slice(1), 16);
            const r = (bigint >> 16) & 255;
            const g = (bigint >> 8) & 255;
            const b = bigint & 255;
            return [r / 255, g / 255, b / 255, 1];
        }
    </script>
</body>
</html>