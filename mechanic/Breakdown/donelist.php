<?php
session_start();
require '../navbar/nav.php';
require '../../connection.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$jobdonelist_data = [];
$user_id = $_SESSION['userID'];
$stmt = $conn->prepare("SELECT * FROM vehicleissuesdone WHERE mech_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $jobdonelist_data[] = $row;
    }
} else {
    echo "No results found.";
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Done List</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="donelist.css">
</head>
<body>
    <div class="main_container">
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Vehicle ID</th>
                        <th>Issue</th>
                        <th>Completed At</th>
                        <th>Status</th>
                        <th>City</th>
                    </tr>
                </thead>
                <tbody id="jobdonelist-tbody">
                    <?php foreach ($jobdonelist_data as $row): ?>
                        <tr>
                            <td data-cell="Vehicle ID"><?= htmlspecialchars($row['v_id']) ?></td>
                            <td data-cell="Issue"><?= htmlspecialchars($row['vehicle_issue']) ?></td>
                            <td data-cell="Completed At"><?= htmlspecialchars($row['job_done_at']) ?></td>
                            <td data-cell="Status"><?= htmlspecialchars($row['status']) ?></td>
                            <td data-cell="City"><?= htmlspecialchars($row['city']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
