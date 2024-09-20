<?php
session_start();
require '../navbar/navbar.php';
include_once('../../connection.php');


$shop_data = [];
$stmt = $conn->prepare("SELECT id, ownerEmail, shop_name, email, number, address, branch FROM shops");
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $shop_data[] = $row;
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
    <title>Shop Manage</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="view_shop.css">
</head>
<body>
<div class="main_container">

    <?php if ($message == 'delete_success'): ?>
        <div class="alert alert-danger">Shop Profile deleted successfully.</div>
    <?php elseif ($message == 'edit_success'): ?>
        <div class="alert alert-success">Shop Profile edited successfully.</div>
    <?php elseif ($message == 'error'): ?>
        <div class="alert alert-danger">Something went wrong: <?= htmlspecialchars($_GET['error'] ?? '') ?></div>
    <?php endif; ?>

    <div class="title">
        <h1>Manage Shops</h1>
        <br><br>
    </div>
    <div class="searchbars">
        
        <div class="search-bar">
            <label for="search">Search</label>
            <input type="text" id="search" class="search-select" placeholder="Shop Name">
        </div>
        <br>
    </div>

    
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Shop Name</th>
                    <th>Owner Email</th>
                    <th>Email</th>
                    <th>Number</th>
                    <th>Address</th>
                    <th>Branch</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="shop-tbody">
                <?php foreach ($shop_data as $row): ?>
                    <tr>
                        <td data-cell="Shop Name"><?= htmlspecialchars($row['shop_name']) ?></td>
                        <td data-cell="Owner Email"><?= htmlspecialchars($row['ownerEmail']) ?></td>
                        <td data-cell="Email"><?= htmlspecialchars($row['email']) ?></td>
                        <td data-cell="Number"><?= htmlspecialchars($row['number']) ?></td>
                        <td data-cell="Address"><?= htmlspecialchars($row['address']) ?></td>
                        <td data-cell="Branch"><?= htmlspecialchars($row['branch']) ?></td>
                        <td>
                            <button class="manage-button view-link" 
                                    data-id="<?= htmlspecialchars($row['id']) ?>"
                                    data-owneremail="<?= htmlspecialchars($row['ownerEmail']) ?>"
                                    data-shopname="<?= htmlspecialchars($row['shop_name']) ?>"
                                    data-email="<?= htmlspecialchars($row['email']) ?>"
                                    data-number="<?= htmlspecialchars($row['number']) ?>"
                                    data-address="<?= htmlspecialchars($row['address']) ?>"
                                    data-branch="<?= htmlspecialchars($row['branch']) ?>">
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
            <h2>Manage Shop</h2>
            <form id="manageBatchForm" action="shop_manage.php" method="POST">
                <input type="hidden" id="manage_shop_id" name="id">

                <div class="form-group">
                    <label for="manage_shop_name">Shop Name:</label>
                    <input type="text" id="manage_shop_name" name="shop_name" required>
                </div>

                <div class="form-group">
                    <label for="manage_owner_email">Owner Email:</label>
                    <input type="email" id="manage_owner_email" name="ownerEmail" required>
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
document.querySelectorAll('.manage-button').forEach(button => {
    button.addEventListener('click', function() {
        var id = this.dataset.id;
        var ownerEmail = this.dataset.owneremail;
        var shopName = this.dataset.shopname;
        var email = this.dataset.email;
        var number = this.dataset.number;
        var address = this.dataset.address;
        var branch = this.dataset.branch;

        document.getElementById('manage_shop_id').value = id;
        document.getElementById('manage_owner_email').value = ownerEmail;
        document.getElementById('manage_shop_name').value = shopName;
        document.getElementById('manage_email').value = email;
        document.getElementById('manage_number').value = number;
        document.getElementById('manage_address').value = address;
        document.getElementById('manage_branch').value = branch;

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
        var confirmed = confirm("Are you sure you want to delete this shop?");
        if (!confirmed) {
            event.preventDefault();
        }
    }
});

document.getElementById("search").addEventListener("input", function() {
    var searchQuery = this.value.toLowerCase();
    var rows = document.querySelectorAll("#shop-tbody tr");
    rows.forEach(function(row) {
        var shopName = row.querySelector("td[data-cell='Shop Name']").textContent.toLowerCase();
        if (shopName.includes(searchQuery)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>
</body>
</html>
