<!DOCTYPE html>
<html lang="en">

<head>
    <title>3D Model</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="generator" content="Geany 1.37.1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://unpkg.com/@webcomponents/webcomponentsjs@2.1.3/webcomponents-loader.js"></script>

    <link rel="stylesheet" href="style.css">
   
</head>

<body>
    <!-- Dropdown Car Selection-->
    <div class="dropdown-container">
        <h2>Choose Your Car Model:</h2>
        <select id="carSelect">
            <option value="benz/scene.gltf">Benz</option>
            <option value="BMW/scene.gltf">BMW</option>
            <option value="scene.gltf">Honda</option>
            <option value="mitshubishi/scene.gltf">Mitshubishi</option>
        </select>
    </div>

    <!-- 3D content -->
    <model-viewer id="carModel" src="benz/scene.gltf" alt="A 3D model of a car" shadow-intensity="1" camera-controls auto-rotate ar>
    </model-viewer>

    <!-- Color Boxes -->
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


    <div class="back-btn">
         <a href="../../shop/shop.php" class="button modify">Back to Shop</a>
    </div>



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
                } else if (carModel.src.includes('benz')){
                    carModel.model.materials[7].pbrMetallicRoughness.setBaseColorFactor(hexToRgb(color));
                } else if (carModel.src.includes('mitshubishi')){
                    carModel.model.materials[0].pbrMetallicRoughness.setBaseColorFactor(hexToRgb(color));
                }
                else {
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
