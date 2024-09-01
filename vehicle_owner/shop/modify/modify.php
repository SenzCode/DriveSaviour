<!DOCTYPE html>
<html lang="en">

<head>
    <title>3D Model</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="generator" content="Geany 1.37.1" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="style.css" rel="stylesheet">
    <script src="https://unpkg.com/@webcomponents/webcomponentsjs@2.1.3/webcomponents-loader.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: white;
            overflow: hidden;
        }

        model-viewer {
            width: 100%;
            height: 130vh;
            position: relative;
            background-color: white;
        }

        .color-boxes-container {
            position: absolute;
            bottom: 30px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .color-boxes {
            display: flex;
            gap: 10px;
        }

        .color-box {
            width: 30px;
            height: 30px;
            cursor: pointer;
            border: 2px solid #000;
        }

        .color-boxes-container .color-boxes .text h2 {
            color: rgb(0, 0, 0);
            font-size: 16px;
            margin-top: 10px;
            font-family: sans-serif;
            font-weight: 600;
        }

        .box-border {
            display: flex;
            padding: 5px;
        }

        .dropdown-container {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            width: 20%;
            z-index: 1; /* Ensure it stays on top of other elements */
        }
        .dropdown-container h2 {
            color: rgb(0, 0, 0);
            font-weight: 600;
            font-size: 16px;
            font-family: sans-serif;
            margin-bottom: 20px;
        }

        .dropdown-container select {
            padding: 10px;
            text-align: left;
            font-size: 16px;
            border: 2px solid #000;
            border-radius: 20px;
            width: 150px;
            background-color: #fff;
            cursor: pointer;
        }

        .dropdown-container select:focus {
            outline: none;
            border-color: #007bff;
        }
        .button {
            margin-top: 200px;
            width: 150px;
            height: 50px;
            color: #182431;
            font-weight: bold;
            padding: 10px;
            border: 1px solid #2B5AC2;
            border-radius: 88px;
            background-color: #f0f8ff;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease, color 0.3s ease;
         }

         .back-btn {
            position: absolute;
            bottom: 30px; 
            left: 30px; 
        }

        .button:hover {
            background-color: #2B5AC2;
            color: #ffffff;
            cursor: pointer;
        }
        @media only screen and (max-width: 600px) {
            model-viewer {
                height: 60vh; 
            }
        
            .dropdown-container {
                width: 80%; 
                top: 10px; 
            }
        
            .dropdown-container select {
                width: 100%; 
            }
        
            .color-boxes-container {
                bottom: 70px; 
            }
        
            .color-boxes {
                flex-direction: column; 
                align-items: center;
            }
        
            .color-box {
                width: 40px; 
                height: 40px;
            }
        
            .button {
                margin-top: 70px;
                width: 100%; 
            }
        }

        @media only screen and (min-width: 601px) and (max-width: 1024px) {
            model-viewer {
                height: 80vh; 
           }
        
            .dropdown-container {
                width: 50%;
            }
        
            .dropdown-container select {
                width: 100%; 
            }
        
            .color-boxes-container {
                bottom: 20px; 
            }
        
            .color-box {
                width: 35px; 
                height: 35px;
            }
        
            .button {
                margin-top: 100px; 
                width: 80%; 
            }
        }
    </style>
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
