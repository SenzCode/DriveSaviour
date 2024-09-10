<?php
// Start the session
session_start();

// Check if the session variable is set and not null
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$loggedInOwnerEmail = $_SESSION['email'];

require('../navbar/nav.php');
include_once('../../connection.php');

// Fetch all data from the shops table where the ownerEmail matches the logged-in user's email
$shop_data = [];
if (!empty($loggedInOwnerEmail)) {
    $stmt = $conn->prepare("SELECT * FROM shops WHERE ownerEmail = ?");
    $stmt->bind_param("s", $loggedInOwnerEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $shop_data[] = $row;
        }
    } else {
        echo "No results found for the given owner email.";
    }
    $stmt->close();
} else {
    echo "No logged-in owner email found.";
}

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Manage</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<div class="main_container">
    <?php if ($message == 'insert'): ?>
        <div class="alert alert-success">The Shop was created successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger">The Shop was deleted successfully.</div>
    <?php elseif ($message == 'edit'): ?>
        <div class="alert alert-success">The Shop was updated successfully.</div>
    <?php elseif ($message == 'error'): ?>
        <div class="alert alert-danger">Something went wrong: <?= htmlspecialchars($_GET['error'] ?? '') ?></div>
    <?php endif; ?>

    <br>
  
    <div class="title">
        <h1>Shop Manage</h1>
        <br><br>     
    </div>

    <form action="addshop.php" method="POST">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="shop_name">Shop Name:</label>
                    <input type="text" id="shop_name" name="shop_name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="number">Number:</label>
                    <input type="text" id="number" name="number" required>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="branch">Branch:</label>
                    <input type="text" id="branch" name="branch" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="ownerEmail">Owner Email:</label>
                    <input type="text" id="ownerEmail" name="ownerEmail" value="<?= htmlspecialchars($loggedInOwnerEmail) ?>" required readonly>
                </div>
            </div>
        </div>
        <br>
        <button type="submit" name="action" value="insert" class="batch view-link">Add Shop</button>
    </form>


    <div class="searchbars">
        <!-- Search bar -->
        <div class="search-bar">
            <label for="search">Search by Shop Name:</label>
            <input type="text" id="search" class="search-select" placeholder="Shop Name">
            <button id="search-icon"><i class="fas fa-search"></i></button>
        </div>
        <br>
    </div>

    <!-- Table -->
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Shop Name</th>
                    <th>Email</th>
                    <th>Number</th>
                    <th>Address</th>
                    <th>Branch</th>
                    <th>Owner </th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="course-tbody">
                <?php foreach ($shop_data as $row): ?>
                    <tr>
                        <td data-cell="Shop Name"><?= htmlspecialchars($row['shop_name']) ?></td>
                        <td data-cell="Email"><?= htmlspecialchars($row['email']) ?></td>
                        <td data-cell="Number"><?= htmlspecialchars($row['number']) ?></td>
                        <td data-cell="Address"><?= htmlspecialchars($row['address']) ?></td>
                        <td data-cell="Branch"><?= htmlspecialchars($row['branch']) ?></td>
                        <td data-cell="Owner Email"><?= htmlspecialchars($row['ownerEmail']) ?></td>
                        <td>
                            <button class="manage-button view-link" 
                                    data-id="<?= htmlspecialchars($row['id']) ?>"
                                    data-shop_name="<?= htmlspecialchars($row['shop_name']) ?>"
                                    data-email="<?= htmlspecialchars($row['email']) ?>"
                                    data-number="<?= htmlspecialchars($row['number']) ?>"
                                    data-address="<?= htmlspecialchars($row['address']) ?>"
                                    data-branch="<?= htmlspecialchars($row['branch']) ?>"
                                    data-ownerEmail="<?= htmlspecialchars($row['ownerEmail']) ?>">
                                Manage
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- manage modal -->
    <div id="manageBatchModal" class="modal">
        <div class="modal-content">
            <span id="closeManageBatchModal" class="close">&times;</span>
            <h2>Manage Shop</h2>
            <form id="manageBatchForm" action="shop_manage.php" method="POST">
                <input type="hidden" id="manage_shop_id" name="id">
                <div class="form-group">
                    <label for="manage_shop_name">Shop Name:</label>
                    <input type="text" id="manage_shop_name" name="shop_name" required>
                </div>
                <div class="form-group">
                    <label for="manage_email">Email:</label>
                    <input type="email" id="manage_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="manage_number">Number:</label>
                    <input type="text" id="manage_number" name="number" required>
                </div>
                <div class="form-group">
                    <label for="manage_address">Address:</label>
                    <input type="text" id="manage_address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="manage_branch">Branch:</label>
                    <input type="text" id="manage_branch" name="branch" required>
                </div>
                
                <br>
                <button type="submit" name="action" value="edit" class="batch view-link">Edit</button>
                <button type="submit" name="action" value="delete" class="batch delete-link">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    var manageBatchModal = document.getElementById("manageBatchModal");
    var closeManageBatchModal = document.getElementById("closeManageBatchModal");

    // Close modal when close button is clicked
    closeManageBatchModal.onclick = function() {
        manageBatchModal.style.display = "none";
    }

    // Close modal when user clicks outside of it
    window.onclick = function(event) {
        if (event.target == manageBatchModal) {
            manageBatchModal.style.display = "none";
        }
    }

    // Manage Shop functionality
    document.querySelectorAll('.manage-button').forEach(button => {
        button.addEventListener('click', function() {
            var shopId = this.dataset.id;
            var shopName = this.dataset.shop_name;
            var email = this.dataset.email;
            var number = this.dataset.number;
            var address = this.dataset.address;
            var branch = this.dataset.branch;
            // var ownerEmail = this.dataset.ownerEmail;

            document.getElementById('manage_shop_id').value = shopId;
            document.getElementById('manage_shop_name').value = shopName;
            document.getElementById('manage_email').value = email;
            document.getElementById('manage_number').value = number;
            document.getElementById('manage_address').value = address;
            document.getElementById('manage_branch').value = branch;
            // document.getElementById('manage_ownerEmail').value = ownerEmail;

            manageBatchModal.style.display = "block";
        });
    });

    // Confirm deletion for Shop
    document.getElementById("manageBatchForm").addEventListener("submit", function(event) {
        var action = document.activeElement.value;
        if (action === 'delete') {
            var confirmed = confirm("Are you sure you want to delete this shop?");
            if (!confirmed) {
                event.preventDefault();
            }
        }
    });
</script>

</body>
</html>
