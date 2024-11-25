<?php
session_start();
require '../navbar/navbar.php';
include_once('../../connection.php');


$mechanic_data = [];
$stmt = $conn->prepare("SELECT * FROM mechanic");
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $mechanic_data[] = $row;
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
    <title>Mechanic Manage</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="../Vehicle Owners/view_owners.css">
</head>
<body>
<div class="main_container">

    <?php if ($message == 'delete_success'): ?>
        <div class="alert alert-danger">Mechanic Profile deleted successfully.</div>
    <?php elseif ($message == 'edit_success'): ?>
        <div class="alert alert-success">Mechanic Profile edited successfully.</div>
    <?php elseif ($message == 'error'): ?>
        <div class="alert alert-danger">Something went wrong: <?= htmlspecialchars($_GET['error'] ?? '') ?></div>
    <?php endif; ?>
    
    <div class="title">
        <h1>Manage Mechanics</h1>
        <br><br>
    </div>
    <div class="searchbars">
       
        <div class="search-bar">
            <label for="search">Search</label>
            <input type="text" id="search" class="search-select" placeholder="Mechanic Name">
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="mechanic-tbody">
                <?php foreach ($mechanic_data as $row): ?>
                    <tr>
                        <td data-cell="Name"><?= htmlspecialchars($row['name']) ?></td>
                        <td data-cell="Email"><?= htmlspecialchars($row['email']) ?></td>
                        <td data-cell="Phone"><?= htmlspecialchars($row['phone']) ?></td>
                        <td data-cell="Address"><?= htmlspecialchars($row['address']) ?></td>
                        <td>
                            <button class="manage-button view-link" 
                                    data-id="<?= htmlspecialchars($row['userID']) ?>"
                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-email="<?= htmlspecialchars($row['email']) ?>"
                                    data-phone="<?= htmlspecialchars($row['phone']) ?>"
                                    data-address="<?= htmlspecialchars($row['address']) ?>"
                                    >
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
            <h2>Manage Mechanic</h2>
            <form id="manageBatchForm" action="mechanic_manage.php" method="POST">
                <input type="hidden" id="manage_user_id" name="userID">

                <div class="form-group">
                    <label for="manage_name">Mechanic Name:</label>
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
        var userID = this.dataset.id;
        var name = this.dataset.name;
        var email = this.dataset.email;
        var phone = this.dataset.phone;
        var address = this.dataset.address;
        var dob = this.dataset.dob;

        document.getElementById('manage_user_id').value = userID;
        document.getElementById('manage_name').value = name;
        document.getElementById('manage_email').value = email;
        document.getElementById('manage_phone').value = phone;
        document.getElementById('manage_address').value = address;

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
        var confirmed = confirm("Are you sure you want to delete this mechanic?");
        if (!confirmed) {
            event.preventDefault();
        }
    }
});

document.getElementById("search").addEventListener("input", function() {
    var searchQuery = this.value.toLowerCase();
    var rows = document.querySelectorAll("#mechanic-tbody tr");
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
