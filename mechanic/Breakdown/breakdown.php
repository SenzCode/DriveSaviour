<?php
session_start();
ob_start(); // header is already used in the navbar.
require '../navbar/nav.php';
require '../../connection.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../Login/login.php");
    exit;
}

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
}

$sql = "SELECT vi.*, vo.name 
from vehicleissues vi
    JOIN vehicle_owner vo ON vi.email = vo.email WHERE mech_id IS NULL";
$result = $conn->query($sql);

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find a Mech</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../vehicle_owner/profile/style.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>

<div class="container-post">
    <?php if (!empty($success_message)): ?>
        <p class="success"><?= $success_message; ?></p>
    <?php elseif (!empty($error_message)): ?>
        <p class="error"><?= $error_message; ?></p>
    <?php endif; ?>

    <div class="issues-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="issue-card">
                    <div class="card-header">
                        <h5><?= htmlspecialchars($row['name']); ?></h5>
                    </div>
                    <div class="card-body">
                        <p><span class="issue-title">Email: </span><?= htmlspecialchars($row['email']); ?></p>
                        <p><span class="issue-title">Vehicle Model: </span><?= htmlspecialchars($row['vehicle_model']); ?></p>
                        <p><span class="issue-title">Year: </span><?= htmlspecialchars($row['year']); ?></p>
                        <p><span class="issue-title">Mobile Number: </span><?= htmlspecialchars($row['mobile_number']); ?></p>
                        <!-- <p><span class="issue-title">Location: </span><?= htmlspecialchars($row['location']); ?></p> -->
                        <p><span class="issue-title">Vehicle Issue: </span><?= htmlspecialchars($row['vehicle_issue']); ?></p>
                        <button class="btn" onclick="window.location.href='view_issue.php?id=<?= $row['id']; ?>'">Check</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No job posts found.</p>
        <?php endif; ?>
    </div>
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

<?php
$conn->close();
?>
