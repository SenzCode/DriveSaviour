<?php
    session_start();
    require '../../connection.php';
    require '../navbar/nav.php';

    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

    // Query to fetch vehicle issues and related mechanic info
    $breakdown = "SELECT vi.*, mech.name AS mech_name, mech.*, v.* FROM vehicleissuesdone vi
                  LEFT JOIN mechanic mech ON mech.userID = vi.mech_id
                  LEFT JOIN vehicle v ON v.v_id = vi.v_id
                  WHERE vi.email = ?
                  ORDER BY vi.job_done_at DESC";

    $stmt = $conn->prepare($breakdown);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    $breakdown = $stmt->get_result();
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
        <!-- Ongoing Breakdowns Section -->
        <div id="ongoing-breakdowns" style="display:none;">
            <h1>Ongoing Breakdowns</h1>
            <div class="vehicle-card-container">
                <!-- Cards with status 'Pending' will be injected here -->
            </div>
        </div>

        <!-- Breakdown List Section -->
        <div id="breakdown-list">
            <h1>Breakdown List</h1>
            <div class="vehicle-card-container">
                <!-- All other cards will be injected here -->
            </div>
        </div>

        <div class="vehicle_details">
            <?php 
            $hasPending = false; // Track if there's any pending issue

            if ($breakdown->num_rows > 0) { 
                while ($vehicle = $breakdown->fetch_assoc()) {
                    // Check if the issue is pending or not
                    $isPending = $vehicle['status'] === 'Pending';

                    if ($isPending) {
                        $hasPending = true; // Set flag if any issue is pending
                    }

                    // Use the appropriate container based on the status
                    ?>
                    <div class="vehicle-card" id="card-<?php echo $vehicle['id']; ?>" data-status="<?php echo $isPending ? 'pending' : 'resolved'; ?>">
                        <h3><?php echo $vehicle['model']; ?> (<?php echo $vehicle['year']; ?>)</h3>
                        <p><strong>Breakdown Issue:</strong> <?php echo $vehicle['vehicle_issue']; ?></p>
                        <p><strong>Date and Time:</strong> <?php echo $vehicle['job_done_at']; ?></p>
                        <p><strong>Breakdown Status:</strong> <?php echo $vehicle['status']; ?></p>
                        <p><strong>Mechanic's Name:</strong> <?php echo $vehicle['mech_name']; ?></p>
                        <p><strong>Mechanic's Contact:</strong> <?php echo $vehicle['phone']; ?></p>

                        <button class="btn" 
                            onclick="openUpdateModal(
                                <?php echo $vehicle['id']; ?>,
                                '<?php echo addslashes($vehicle['model']); ?>', 
                                '<?php echo addslashes($vehicle['job_done_at']); ?>',
                                '<?php echo addslashes($vehicle['vehicle_issue']); ?>')">Update
                        </button>

                        <button class="btn" onclick="confirmDelete(<?php echo $vehicle['id']; ?>)">Delete</button>
                        <button class="btn" onclick="openRateModal(<?php echo $vehicle['userID']; ?>, '<?php echo $vehicle['name']; ?>')">Rate</button>
                    </div>
                    <?php 
                } 
            } else { ?>
                <p>No vehicle issues for this user.</p>
            <?php } ?>
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
            
            // Move cards into respective containers
            if (ongoingCards.length > 0) {
                ongoingBreakdownsContainer.style.display = 'block'; // Show the ongoing section
                ongoingCards.forEach(card => {
                    ongoingBreakdownsContainer.querySelector('.vehicle-card-container').appendChild(card);
                });
            } else {
                ongoingBreakdownsContainer.style.display = 'none'; // Hide if no pending issues
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
</html>
