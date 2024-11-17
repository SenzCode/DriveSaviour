<?php
session_start();
require '../../connection.php';
require '../navbar/nav.php';

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// fetching ongoing vehicle issues
$ongoingQuery = "SELECT vi.*, mech.name AS mech_name, mech.*, v.* FROM vehicleissues vi
                 LEFT JOIN mechanic mech ON mech.userID = vi.mech_id
                 LEFT JOIN vehicle v ON v.v_id = vi.v_id
                 WHERE vi.email = ? AND vi.status = 'Pending'
                 ORDER BY vi.created_at DESC";

$ongoingStmt = $conn->prepare($ongoingQuery);
if (!$ongoingStmt) {
    die("Error preparing statement: " . $conn->error);
}
$ongoingStmt->bind_param("s", $email);
$ongoingStmt->execute();
$ongoingIssues = $ongoingStmt->get_result();

// fetching completed vehicle issues
$completedQuery = "SELECT vi.*, mech.name AS mech_name, mech.*, v.* FROM vehicleissuesdone vi
                   LEFT JOIN mechanic mech ON mech.userID = vi.mech_id
                   LEFT JOIN vehicle v ON v.v_id = vi.v_id
                   WHERE vi.email = ?
                   ORDER BY vi.job_done_at DESC";

$completedStmt = $conn->prepare($completedQuery);
if (!$completedStmt) {
    die("Error preparing statement: " . $conn->error);
}
$completedStmt->bind_param("s", $email);
$completedStmt->execute();
$completedIssues = $completedStmt->get_result();

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="break-style.css">
</head>
<body>
    <div class="body">

        <div class="order-header">
            <button class="back-btn" onclick="window.location.href='mech.php'">&larr; Back</button>
        </div>

        <!-- Ongoing Breakdowns Section -->
        <div id="ongoing-breakdowns" style="display:<?php echo $ongoingIssues->num_rows > 0 ? 'block' : 'none'; ?>;">
            <h1>Ongoing Breakdowns</h1>
            <?php if ($message == 'delete'): ?>
                <div class="alert alert-success" id="success-alert">The issue is deleted successfully.</div>
            <?php elseif ($message == 'update'): ?>
                <div class="alert alert-success" id="success-alert">The issue is updated successfully.</div>
            <?php endif; ?>
            <div class="vehicle-card-container">
                <?php 
                while ($vehicle = $ongoingIssues->fetch_assoc()) { ?>
                    <div class="vehicle-card" id="card-<?php echo $vehicle['id']; ?>" data-status="pending">
                        <h3><?php echo $vehicle['model']; ?> (<?php echo $vehicle['year']; ?>)</h3>
                        <p><strong>Breakdown Issue:</strong> <?php echo $vehicle['vehicle_issue']; ?></p>
                        <p><strong>Date and Time:</strong> <?php echo $vehicle['created_at']; ?></p>
                        <p><strong>Breakdown Status:</strong> <?php echo $vehicle['status']; ?></p>
                        <p><strong>Mechanic's Name:</strong> <?php echo $vehicle['mech_name']; ?></p>
                        <p><strong>Mechanic's Contact:</strong> <?php echo $vehicle['phone']; ?></p>

                        <button class="btn" onclick="openUpdateModal(<?php echo $vehicle['id']; ?>, '<?php echo addslashes($vehicle['model']); ?>', '<?php echo addslashes($vehicle['created_at']); ?>', '<?php echo addslashes($vehicle['vehicle_issue']); ?>')">Update</button>
                        <button class="btn" onclick="confirmDelete(<?php echo $vehicle['id']; ?>)">Delete</button>
                        <button class="btn" onclick="openRateModal(<?php echo $vehicle['userID']; ?>, '<?php echo $vehicle['mech_name']; ?>')">Rate</button>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Breakdown List Section -->
        <div id="breakdown-list">
            <h1>Breakdown History</h1>
            <div class="vehicle-card-container">
                <?php 
                if ($completedIssues->num_rows > 0) { 
                    while ($vehicle = $completedIssues->fetch_assoc()) { ?>
                        <div class="vehicle-card" id="card-<?php echo $vehicle['id']; ?>" data-status="resolved">
                            <h3><?php echo $vehicle['model']; ?> (<?php echo $vehicle['year']; ?>)</h3>
                            <p><strong>Breakdown Issue:</strong> <?php echo $vehicle['vehicle_issue']; ?></p>
                            <p><strong>Date and Time:</strong> <?php echo $vehicle['job_done_at']; ?></p>
                            <p><strong>Breakdown Status:</strong> <?php echo $vehicle['status']; ?></p>
                            <p><strong>Mechanic's Name:</strong> <?php echo $vehicle['mech_name']; ?></p>
                            <p><strong>Mechanic's Contact:</strong> <?php echo $vehicle['phone']; ?></p>

                            <button class="btn" onclick="openRateModal(<?php echo $vehicle['userID']; ?>, '<?php echo $vehicle['mech_name']; ?>')">Rate</button>
                        </div>
                    <?php 
                    }
                } else { ?>
                    <p>No breakdowns found.</p>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateModal()">&times;</span> <!-- Close icon -->
            <h2>Update Vehicle Issue</h2>
            <!-- The form now posts directly to update_issue.php -->
            <form id="updateForm" action="update_issue.php" method="POST">
                <input type="hidden" id="updateIssueID" name="issueID">
                <span for="vehicleModel">Vehicle Model:</span>
                <input type="text" id="vehicleModel" name="vehicleModel" readonly>
                <span for="dateTime">Date and Time:</span>
                <input type="text" id="dateTime" readonly>
                <span for="vehicleIssue">Issue:</span>
                <textarea id="vehicleIssue" name="vehicleIssue"></textarea>
                <button type="submit" class="btn">Update</button>
            </form>
        </div>
    </div>

    <!-- Rate Modal -->
    <div id="rateModal" class="modal" style="display:none;">
        <h2>Rate Mechanic</h2>
        <p>Mechanic Name: <span id="mechanicName"></span></p>
        <div class="rating">
            <span onclick="rate(5)">&#9733;</span>
            <span onclick="rate(4)">&#9733;</span>
            <span onclick="rate(3)">&#9733;</span>
            <span onclick="rate(2)">&#9733;</span>
            <span onclick="rate(1)">&#9733;</span>
        </div>
        <button onclick="submitRating()">Submit Rating</button>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ongoingBreakdownsContainer = document.getElementById('ongoing-breakdowns');
            const ongoingCards = document.querySelectorAll('[data-status="pending"]');
            const breakdownListContainer = document.getElementById('breakdown-list');
            const otherCards = document.querySelectorAll('[data-status="resolved"]');
            
            if (ongoingCards.length > 0) {
                ongoingBreakdownsContainer.style.display = 'block';
                ongoingCards.forEach(card => {
                    ongoingBreakdownsContainer.querySelector('.vehicle-card-container').appendChild(card);
                });
            } else {
                ongoingBreakdownsContainer.style.display = 'none';
            }

            otherCards.forEach(card => {
                breakdownListContainer.querySelector('.vehicle-card-container').appendChild(card);
            });
        });

        // Open Update Modal
        function openUpdateModal(issueID, vehicleModel, dateTime, vehicleIssue) {
            document.getElementById('updateIssueID').value = issueID;
            document.getElementById('vehicleModel').value = vehicleModel;
            document.getElementById('dateTime').value = dateTime;
            document.getElementById('vehicleIssue').value = vehicleIssue;

            document.getElementById('updateModal').style.display = 'block';
        }

        // Close Update Modal
        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        // DELETE PROCESS
        function confirmDelete(issueID) {
            if (confirm('Are you sure to delete this issue?')) {
                fetch('delete_issue.php', {
                    method: 'POST',
                    body: JSON.stringify({ issueID: issueID }),
                    headers: { 'Content-Type': 'application/json' }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('card-' + issueID).remove(); // Remove the card
                    }
                });
            }
        }
    </script>

    <?php
        require '../footer/footer.php';
    ?>
    
</body>

<script>
    setTimeout(function() {
        var alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 10000); // 10 seconds
</script>

</html>
