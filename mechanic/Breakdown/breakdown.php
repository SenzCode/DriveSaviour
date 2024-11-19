<?php
session_start();
require '../navbar/nav.php';
require '../../connection.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../Login/login.php");
    exit;
}

$mechanic_id = $_SESSION['userID'];
$mechanic_address = $_SESSION['address'];
$name = $_SESSION['name'];
$phone = $_SESSION['phone'];
$email = $_SESSION['email'];

// Check if the logged-in mechanic is in the `paid_mechanics` table
$checkPaidMechanicQuery = $conn->prepare("SELECT * FROM paid_mechanics WHERE email = ?");
$checkPaidMechanicQuery->bind_param("s", $email); // Corrected bind_param to use "s" for string
$checkPaidMechanicQuery->execute();
$resultPaid = $checkPaidMechanicQuery->get_result();

if ($resultPaid->num_rows > 0) {
    $paidMechanic = $resultPaid->fetch_assoc();
    $expire_date = $paidMechanic['expire_date'];
    
    // Check if the subscription has expired
    if (strtotime($expire_date) < time()) {
        // Subscription expired, show subscription section
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Subscription Required</title>
            <link rel="stylesheet" href="../navbar/style.css">
            <link rel="stylesheet" href="../Loyalty_card/style.css">
        </head>
        <body>
            <div class="loyalty-card-details">
                <h2>Your Details</h2>
                <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
                <p><strong>City:</strong> <?= htmlspecialchars($mechanic_address) ?></p>
                
                <form action="stripe_payment.php" method="POST">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                    <button type="submit" class="btn">Purchase Subscription</button>
                </form>
            </div>
            <?php require '../../vehicle_owner/footer/footer.php'; ?>
        </body>
        </html>
        <?php
    } else {
        // Subscription is still active, show breakdown details
        $sql_unassigned = "SELECT vi.*, vo.name 
                           FROM vehicleissues vi
                           JOIN vehicle_owner vo ON vi.email = vo.email 
                           WHERE vi.mech_id IS NULL AND vi.city = '$mechanic_address'";
        $result_unassigned = $conn->query($sql_unassigned);

        $sql_accepted = "SELECT vi.*, vo.name 
                         FROM vehicleissues vi
                         JOIN vehicle_owner vo ON vi.email = vo.email 
                         WHERE vi.mech_id = '$mechanic_id' AND vi.city = '$mechanic_address'";
        $result_accepted = $conn->query($sql_accepted);

        $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
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

        <div class="container-post"> <br><br>
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
            <button class="btn1" onclick="window.location.href='donelist.php'">Job Done</button>
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
        <?php require '../../vehicle_owner/footer/footer.php'; ?>
        </body>
        </html>
        <?php
    }
} else {
    // Mechanic is not in `paid_mechanics`: Show subscription details
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Subscription Required</title>
        <link rel="stylesheet" href="../navbar/style.css">
        <link rel="stylesheet" href="../Loyalty_card/style.css">
    </head>
    <body>
        <div class="loyalty-card-details">
            <h2>Your Details</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($phone) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>City:</strong> <?= htmlspecialchars($mechanic_address) ?></p>
            
            <form action="stripe_payment.php" method="POST">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                <button type="submit" class="btn">Purchase Subscription</button>
            </form>
        </div>
        <?php require '../../vehicle_owner/footer/footer.php'; ?>
    </body>
    </html>
    <?php
}

$checkPaidMechanicQuery->close();
$conn->close();
?>
