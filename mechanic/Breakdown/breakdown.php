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
}

// Fetch all job posts
$sql = "SELECT id, first_name, last_name, email, vehicle_model, year, mobile_number, location, vehicle_issue, status FROM VehicleIssues";
$result = $conn->query($sql);
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
            <div class="breakdown-table">
                <table>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Vehicle Model</th>
                        <th>Year</th>
                        <th>Mobile Number</th>
                        <th>Location</th>
                        <th>Vehicle Issue</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['first_name']); ?></td>
                                <td><?= htmlspecialchars($row['last_name']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><?= htmlspecialchars($row['vehicle_model']); ?></td>
                                <td><?= htmlspecialchars($row['year']); ?></td>
                                <td><?= htmlspecialchars($row['mobile_number']); ?></td>
                                <td><?= htmlspecialchars($row['location']); ?></td>
                                <td><?= htmlspecialchars($row['vehicle_issue']); ?></td>
                                <td><?= htmlspecialchars($row['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">No job posts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

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
