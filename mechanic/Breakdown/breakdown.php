<?php
session_start();
ob_start();
require '../navbar/nav.php';
require '../../connection.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../Login/login.php");
    exit;
}

$mechanic_id = $_SESSION['userID'];
$mechanic_address = $_SESSION['address'];

// Query for unassigned vehicle issues where the city matches the mechanic's address
$sql_unassigned = "SELECT vi.*, vo.name 
                   FROM vehicleissues vi
                   JOIN vehicle_owner vo ON vi.email = vo.email 
                   WHERE vi.mech_id IS NULL AND vi.city = '$mechanic_address'";
$result_unassigned = $conn->query($sql_unassigned);

// Query for accepted vehicle issues assigned to this mechanic and city matches
$sql_accepted = "SELECT vi.*, vo.name 
                 FROM vehicleissues vi
                 JOIN vehicle_owner vo ON vi.email = vo.email 
                 WHERE vi.mech_id = '$mechanic_id' AND vi.city = '$mechanic_address'";
$result_accepted = $conn->query($sql_accepted);

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';

ob_end_flush();
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
    <h2 class="vihead">Vehicle Issues</h2>
    <div class="main_container">
        <?php if ($message == 'updated'): ?>
        <div class="alert alert-success" id="success-alert">The issue status updated successfully.</div>
    <?php endif; ?>
    </div>
    <div class="issues-container">
        <?php if ($result_unassigned->num_rows > 0): ?>
            <?php while($row = $result_unassigned->fetch_assoc()): ?>
                <div class="issue-card">
                    <div class="card-header">
                        <h5><?= htmlspecialchars($row['name']); ?></h5>
                    </div>
                    <div class="card-body">
                        <p><span class="issue-title">Email: </span><?= htmlspecialchars($row['email']); ?></p>
                        <p><span class="issue-title">Vehicle Model: </span><?= htmlspecialchars($row['vehicle_model']); ?></p>
                        <p><span class="issue-title">Year: </span><?= htmlspecialchars($row['year']); ?></p>
                        <p><span class="issue-title">Mobile Number: </span><?= htmlspecialchars($row['mobile_number']); ?></p>
                        <p><span class="issue-title">Vehicle Issue: </span><?= htmlspecialchars($row['vehicle_issue']); ?></p>
                        <button class="btn" onclick="window.location.href='view_issue.php?id=<?= $row['id']; ?>'">Check</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No unassigned issues found.</p>
        <?php endif; ?>
    </div>

    <h2 class="vihead">Accepted Vehicle Issues</h2>
    <button class="btn" onclick="window.location.href='donelist.php'">Job Done</button>
    <div class="issues-container">
        <?php if ($result_accepted->num_rows > 0): ?>
            <?php while($row = $result_accepted->fetch_assoc()): ?>
                <div class="issue-card">
                    <div class="card-header">
                        <h5><?= htmlspecialchars($row['name']); ?></h5>
                    </div>
                    <div class="card-body">
                        <p><span class="issue-title">Email: </span><?= htmlspecialchars($row['email']); ?></p>
                        <p><span class="issue-title">Vehicle Model: </span><?= htmlspecialchars($row['vehicle_model']); ?></p>
                        <p><span class="issue-title">Year: </span><?= htmlspecialchars($row['year']); ?></p>
                        <p><span class="issue-title">Mobile Number: </span><?= htmlspecialchars($row['mobile_number']); ?></p>
                        <p><span class="issue-title">Vehicle Issue: </span><?= htmlspecialchars($row['vehicle_issue']); ?></p>
                        
                        <form method="POST" action="update_status.php">
                            <input type="hidden" name="issue_id" value="<?= $row['id']; ?>">
                            <label for="status-<?= $row['id']; ?>">Status:</label>
                            <select name="status" id="status-<?= $row['id']; ?>">
                                <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Done" <?= $row['status'] == 'Done' ? 'selected' : ''; ?>>Done</option>
                            </select>
                            <button type="submit" class="btn">Update</button>
                        </form>

                        <button class="btn" onclick="window.location.href='view_issue.php?id=<?= $row['id']; ?>'">Check</button>
                    </div>


                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No accepted issues found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
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
    });
</script>

<?php
    require '../../vehicle_owner/footer/footer.php';
?>
</body>

<script>
    setTimeout(function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 10000); // 10 seconds
</script>

</html>

<?php
$conn->close();
?>
