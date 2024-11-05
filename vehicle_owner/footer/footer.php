<footer>
    <style>
        footer {
            background-color: #ffffff; 
            padding: 20px 0;
            /* border-top: 1px solid #eaeaea; */
            font-family: Arial, sans-serif;
            color: #333;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode footer{
            box-shadow: 0 4px 15px rgb(199, 199, 199);
        } 

        .footer-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1450px;
            margin: 0 auto;
            padding: 0 20px;
            flex-wrap: wrap;
        }

        .footer-left, .footer-right {
            flex: 1;
            min-width: 250px; 
        }

        .footer-left {
            display: flex;
            align-items: center;
            flex: 2;
        }

        .footer-logo {
            max-width: 150px;
            margin-right: 20px;
        }

        .footer-logo2 {
            max-width: 150px;
            margin-right: 20px;
            display: none; 
        }

        .dark-mode .footer-logo {
            display: none; 
        }

        .dark-mode .footer-logo2 {
            display: block; 
        }

        .footer-left-text {
            display: flex;
            flex-direction: column;
        }

        .footer-left p {
            margin: 0;
            font-size: 14px;
            line-height: 1.5;
            color: #555;
        }

        .social-icons {
            margin-top: 15px;
        }

        .social-icons a {
            margin-right: 10px;
            transition: all .50s ease;
            transition: transform 0.5s ease; 
            display: inline-block;
        }

        .social-icons a:hover {
            transform: scale(1.1);
        }

        .social-icons i {
           font-size: 28px;
           color: black;
        }
        .footer-right {
            text-align: right;
            flex: 1;
        }

        .contact-info p {
            margin-bottom: 10px;
            font-size: 14px;
            line-height: 1.5;
            color: #555;
        }

        .contact-info img {
            vertical-align: middle;
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .footer-bottom {
            text-align: center;
            padding: 10px 0;
            font-weight: 500;
            border-top: 1px solid #ddd;
            margin-top: 20px;
            font-size: 13px;
            color: #aaa;
        }
        body.dark-mode footer{
            color: white;
            background-color: black;
        }
        body.dark-mode .footer-left-text p{
            color: #a5a5a5;
        }

        body.dark-mode .footer-bottom{
            border-top: 1px solid #3e3e3e;
        }

        body.dark-mode .social-icons i{
            color: #c4c4c4;
        }

        body.dark-mode .contact-info p{
            color: #c4c4c4;
        }

        /* body.dark-mode .social-icons {
            width: 200px;
            height: 50px;
            border-radius: 30px;
            background-color: #cccccc;
            display: flex;
            align-items: center; 
            justify-content: center; 
            padding: 10px;
            gap: 20px; 
            box-sizing: border-box; 
        }

        body.dark-mode .social-icons a {
            display: flex;
            align-items: center; 
            justify-content: center; 
            margin: 0; 
        } */

        body.dark-mode .social-icons img {
            width: 24px; 
            height: 24px; 
            display: block; 
        }


        /*Media Queries*/
        @media (max-width: 768px) {
            .footer-container {
                flex-direction: column;
                align-items: center;
                text-align: center; 
            }

            .footer-left, .footer-right {
                text-align: center;
                margin-bottom: 20px;
                width: 100%; 
            }

            .footer-left {
                flex-direction: column;
                align-items: center; 
                margin-bottom: 20px;
            }

            .footer-logo {
                margin: 0 0 10px 0;
            }

            .social-icons {
                margin-top: 20px;
            }
        }

        @media (max-width: 480px) {
            .footer-left, .footer-right {
                font-size: 12px;
            }

            .footer-logo {
                max-width: 120px;
            }

            .social-icons img {
                width: 20px;
                height: 20px;
            }

            .contact-info img {
                width: 18px;
                height: 18px;
            }
            .social-icons {
                margin-top: 20px;
            }
            .contact-info{
                margin-top: 10px;
            }
            .footer-bottom{
                margin-top: -10px;
            }
        }
    </style>

    <div class="footer-container">
        <div class="footer-left">
            <img src="../../img/ss.png" alt="Drive Saviour Logo" class="footer-logo"> 
            <img class="footer-logo2" src="../../img/1s.png" alt="Logo">
            <div class="footer-left-text">
                <p>The centre of Sri Lankan auto repair. We spearhead Sri Lanka's auto care industry's digital transformation.</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-facebook-circle'></i></a> 
                    <a href="#"><i class='bx bxl-whatsapp' ></i></a>
                    <a href="#"><i class='bx bxl-instagram' ></i></a>
                    <a href="#"><i class='bx bxl-youtube' ></i></a>
                </div>
            </div>
        </div>
        <div class="footer-right">
            <div class="contact-info">
                <p>
                     <a href="tel:+94704633566">
                        <img src="../../img/telephone.png" alt="Phone"> +94704633566
                    </a>
                </p> 
                <p>
                    <a href="mailto:drivesaviour.lk@gmail.com">
                        <img src="../../img/gmail.png" alt="Email"> drivesaviour.lk@gmail.com
                     </a>
                </p> 
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>Copyright @2024 SenzCode</p>
    </div>
</footer>
