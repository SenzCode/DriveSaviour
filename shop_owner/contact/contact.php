<?php
     require '../navbar/nav.php';
     require '../../connection.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="contact-container">
        <div class="contact-box">
            <h1>Contact Us</h1>
            <p>We're here to help-reach out to us anytime, and we'll respond as soon as possible.</p>
            <form action="https://api.web3forms.com/submit" method="POST">
                <input type="hidden" name="access_key" value="ae11ec17-6145-472d-87ba-bfd9ba032019">
                <input type="text" name="name" placeholder="Name" class="contact-input" required>
                <input type="email" name="email" placeholder="Email" class="contact-input" required>
                <textarea name="message" cols="30" rows="10" placeholder="Message" class="contact-textarea" required></textarea>
                <input type="submit" class="contact-submit">
            </form>
        </div>
    </div> <br> <br> <br>

    <?php
    require '../footer/footer.php';
    ?>
</body>
</html>
