<?php
require '../navbar/nav.php';
require '../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $vehicle_model = htmlspecialchars($_POST['vehicle_model']);
    $year = intval($_POST['year']);
    $mobile_number = htmlspecialchars($_POST['mobile_number']);
    $location = htmlspecialchars($_POST['location']);
    $vehicle_issue = htmlspecialchars($_POST['vehicle_issue']);

    $sql = "INSERT INTO VehicleIssues (first_name, last_name, email, vehicle_model, year, mobile_number, location, vehicle_issue) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssisss", $first_name, $last_name, $email, $vehicle_model, $year, $mobile_number, $location, $vehicle_issue);

        if ($stmt->execute()) {
            $success_message = "Post Added successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error_message = "Error: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find a Mech</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>

    <div class="container-post">
        
        <?php if (!empty($success_message)): ?>
            <p class="success"><?= $success_message; ?></p>
        <?php elseif (!empty($error_message)): ?>
            <p class="error"><?= $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="vehicle_model">Vehicle Model:</label>
            <input type="text" id="vehicle_model" name="vehicle_model" required>

            <label for="year">Year:</label>
            <input type="number" id="year" name="year" required>

            <label for="mobile_number">Mobile Number:</label>
            <input type="text" id="mobile_number" name="mobile_number" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="vehicle_issue">Vehicle Issue:</label>
            <textarea id="vehicle_issue" name="vehicle_issue" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
        // JavaScript for dark mode toggle
        const toggleSwitch = document.querySelector('.dark-mode-checkbox');
        const currentTheme = localStorage.getItem('theme');

        if (currentTheme) {
            document.documentElement.setAttribute('data-theme', currentTheme);

            if (currentTheme === 'dark') {
                toggleSwitch.checked = true;
            }
        }

        toggleSwitch.addEventListener('change', function() {
            if (this.checked) {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
            }
        });
    </script>
</body>
</html>
