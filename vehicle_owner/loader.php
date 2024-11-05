<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DriveSaviour</title>
    <link rel="stylesheet" href="style.css">    

<style>
*, *:after, *:before {
	 -webkit-box-sizing: border-box;
	 -moz-box-sizing: border-box;
	 -ms-box-sizing: border-box;
	 box-sizing: border-box;
}
 body {
	 font-family: arial;
	 font-size: 16px;
	 margin: 0;
	 background: #fff;
	 color: #000;
}
.preloader-wrap{
	position: fixed;
	left: 0;
	top: 0;
	right: 0;
	bottom: 0;
	z-index: 1000;
	display: flex;
	align-items: center;
	justify-content: center;
}
.preloader {
	position: relative;
	width: 100px;
	height: 100px;
	border-radius: 50%;
	perspective: 780px;
}
.loading-circle {
	position: absolute;
	width: 100%;
	height: 100%;
	box-sizing: border-box;
	border-radius: 50%;
}
.loading-circle-one {
	left: 0%;
	top: 0%;
	animation: loadingCircleOne 1.2s linear infinite;
	border-bottom: 8px solid #722dff;
}
.loading-circle-two {
	top: 0%;
	right: 0%;
	animation: loadingCircleTwo 1.2s linear infinite;
	border-right: 8px solid #722dff;
}
.loading-circle-three {
	right: 0%;
	bottom: 0%;
	animation: loadingCircleThree 1.2s linear infinite;
	border-top: 8px solid #722dff;
}
@keyframes loadingCircleOne {
	0% {
		transform: rotateX(40deg) rotateY(-40deg) rotateZ(0deg);
	}
	100% {
		transform: rotateX(40deg) rotateY(-40deg) rotateZ(360deg);
	}
}
@keyframes loadingCircleTwo {
	0% {
		transform: rotateX(50deg) rotateY(15deg) rotateZ(0deg);
	}
	100% {
		transform: rotateX(50deg) rotateY(15deg) rotateZ(360deg);
	}
}
@keyframes loadingCircleThree {
	0% {
		transform: rotateX(15deg) rotateY(50deg) rotateZ(0deg);
	}
	100% {
		transform: rotateX(15deg) rotateY(50deg) rotateZ(360deg);
	}
}
/*dark mode*/
body.dark-mode .preloader-wrap{
    background-color: #eee;
    color: #fff;
} 
 
</style>
</head>
<body>
<div class="preloader-wrap">
  <div class="preloader">
    <div class="loading-circle loading-circle-one"></div>
    <div class="loading-circle loading-circle-two"></div>
    <div class="loading-circle loading-circle-three"></div>
  </div>
</div>
    <script>
        setTimeout(function() {
            window.location.href = "products/product.php"; //Load Home.php
        }, 1000);
    </script>
</body>
</html>