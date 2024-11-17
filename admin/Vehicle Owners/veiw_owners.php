<?php
session_start();
include_once('../../connection.php');
require '../navbar/navbar.php';

$owner_data = [];
$stmt = $conn->prepare("SELECT name, email, phone, address, city FROM vehicle_owner");
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $owner_data[] = $row;
    }
} else {
    echo "No results found.";
}
$stmt->close();

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Owner Manage</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="view_owners.css">
</head>
<body>
<div class="main_container">
    <?php if ($message == 'delete_success'): ?>
        <div class="alert alert-danger">Vehicle Owner Profile deleted successfully.</div>
    <?php elseif ($message == 'edit_success'): ?>
        <div class="alert alert-success">Vehicle Owner Profile edited successfully.</div>
    <?php elseif ($message == 'error'): ?>
        <div class="alert alert-danger">Something went wrong: <?= htmlspecialchars($_GET['error'] ?? '') ?></div>
    <?php endif; ?>

    <div class="title">
        <h1>Manage Vehicle Owners</h1>
        <br><br>
    </div>

    <div class="searchbars">
        <div class="search-bar">
            <label for="search">Search</label>
            <input type="text" id="search" class="search-select" placeholder="Owner Name">
        </div>
        <br>
    </div>

    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="owner-tbody">
                <?php foreach ($owner_data as $row): ?>
                    <tr>
                        <td data-cell="Name"><?= htmlspecialchars($row['name']) ?></td>
                        <td data-cell="Email"><?= htmlspecialchars($row['email']) ?></td>
                        <td data-cell="Phone"><?= htmlspecialchars($row['phone']) ?></td>
                        <td data-cell="Address"><?= htmlspecialchars($row['address']) ?></td>
                        <td data-cell="City"><?= htmlspecialchars($row['city']) ?></td>
                        <td>
                            <button class="manage-button view-link" 
                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-email="<?= htmlspecialchars($row['email']) ?>"
                                    data-phone="<?= htmlspecialchars($row['phone']) ?>"
                                    data-address="<?= htmlspecialchars($row['address']) ?>"
                                    data-city="<?= htmlspecialchars($row['city']) ?>">
                                <i class='bx bxs-cog'></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="manageBatchModal" class="modal">
        <div class="modal-content">
            <span id="closeManageBatchModal" class="close">&times;</span>
            <h2>Manage Vehicle Owner</h2>
            <form id="manageBatchForm" action="owner_manage.php" method="POST">
                <input type="hidden" id="manage_owner_id" name="id">
                <div class="form-group">
                    <label for="manage_name">Owner Name:</label>
                    <input type="text" id="manage_name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="manage_email">Email:</label>
                    <input type="email" id="manage_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="manage_phone">Phone:</label>
                    <input type="text" id="manage_phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="manage_address">Address:</label>
                    <input type="text" id="manage_address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="manage_city">City:</label>
                    <input type="text" id="manage_city" name="city" required>
                </div>
                <br>
                <button type="submit" name="action" value="edit" class="batch view-link">Edit</button>
                <button type="submit" name="action" value="delete" class="batch delete-link">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.manage-button').forEach(button => {
    button.addEventListener('click', function() {
        var name = this.dataset.name;
        var email = this.dataset.email;
        var phone = this.dataset.phone;
        var address = this.dataset.address;
        var city = this.dataset.city;

        document.getElementById('manage_name').value = name;
        document.getElementById('manage_email').value = email;
        document.getElementById('manage_phone').value = phone;
        document.getElementById('manage_address').value = address;
        document.getElementById('manage_city').value = city;

        document.getElementById('manageBatchModal').style.display = "block";
    });
});

var manageBatchModal = document.getElementById("manageBatchModal");
var closeManageBatchModal = document.getElementById("closeManageBatchModal");

closeManageBatchModal.onclick = function() {
    manageBatchModal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == manageBatchModal) {
        manageBatchModal.style.display = "none";
    }
}

document.getElementById("manageBatchForm").addEventListener("submit", function(event) {
    var action = document.activeElement.value;
    if (action === 'delete') {
        var confirmed = confirm("Are you sure you want to delete this owner?");
        if (!confirmed) {
            event.preventDefault();
        }
    }
});

document.getElementById("search").addEventListener("input", function() {
    var searchQuery = this.value.toLowerCase();
    var rows = document.querySelectorAll("#owner-tbody tr");
    rows.forEach(function(row) {
        var name = row.querySelector("td[data-cell='Name']").textContent.toLowerCase();
        if (name.includes(searchQuery)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>
</body>
</html>
