<?php
session_start();
include_once('../../connection.php');
require '../navbar/navbar.php';

// Fetch shop data
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
    <div class="title">
        <h1>Manage Shops</h1>
    </div>

    <div class="search-bar">
        <input type="text" id="search" class="search-select" placeholder="Search Shops">
        <i class="bx bx-search"></i> <!-- Search Icon -->
    </div>

    <?php if ($message == 'delete_success'): ?>
        <div class="alert alert-danger" id="success-alert">Shop Profile deleted successfully.</div>
    <?php elseif ($message == 'edit_success'): ?>
        <div class="alert alert-success" id="success-alert">Shop Profile edited successfully.</div>
    <?php elseif ($message == 'error'): ?>
        <div class="alert alert-danger">Something went wrong: <?= htmlspecialchars($_GET['error'] ?? '') ?></div>
    <?php endif; ?>

    <div class="shop-list">
        <?php foreach ($shop_data as $row): ?>
            <div class="shop-item" data-shop-name="<?= strtolower($row['shop_name']); ?>">
                <div class="shop-info">
                    <strong>Shop Name:</strong> <?= htmlspecialchars($row['shop_name']) ?>
                </div>
                <div class="shop-info">
                    <strong>Owner Email:</strong> <?= htmlspecialchars($row['ownerEmail']) ?>
                </div>
                <div class="shop-info">
                    <strong>Email:</strong> <?= htmlspecialchars($row['email']) ?>
                </div>
                <div class="shop-info">
                    <strong>Number:</strong> <?= htmlspecialchars($row['number']) ?>
                </div>
                <div class="shop-info">
                    <strong>Address:</strong> <?= htmlspecialchars($row['address']) ?>
                </div>
                <div class="shop-info">
                    <strong>Branch:</strong> <?= htmlspecialchars($row['branch']) ?>
                </div>
                <div class="shop-actions">
                    <button class="manage-button" 
                            data-id="<?= htmlspecialchars($row['id']) ?>"
                            data-owneremail="<?= htmlspecialchars($row['ownerEmail']) ?>"
                            data-shopname="<?= htmlspecialchars($row['shop_name']) ?>"
                            data-email="<?= htmlspecialchars($row['email']) ?>"
                            data-number="<?= htmlspecialchars($row['number']) ?>"
                            data-address="<?= htmlspecialchars($row['address']) ?>"
                            data-branch="<?= htmlspecialchars($row['branch']) ?>">
                            <i class='bx bxs-cog'></i> Manage
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal for Editing or Deleting Shop -->
    <div id="manageBatchModal" class="modal">
        <div class="modal-content">
            <span id="closeManageBatchModal" class="close">&times;</span>
            
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
                <button type="submit" name="action" value="edit" class="batch">Edit</button> <br><br>
                <button type="submit" name="action" value="delete" class="batch delete-link">Delete</button>
            </form>
        </div>
    </div>

</div>

<script>
// Handle modal pop-up for edit/delete
function attachManageButtonEventListeners() {
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
}

// Attach event listeners initially
attachManageButtonEventListeners();

// Close modal on clicking close or outside the modal
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

// Search function
document.getElementById("search").addEventListener("input", function() {
    var searchQuery = this.value.toLowerCase();
    var items = document.querySelectorAll(".shop-item");
    
    var noMatchFound = true;
    
    items.forEach(function(item) {
        var shopName = item.getAttribute("data-shop-name");
        
        if (shopName.includes(searchQuery)) {
            item.style.display = "block";
            noMatchFound = false;
        } else {
            item.style.display = "none";
        }
    });
    
    // If no shop name matches and search is not empty, show "No results found."
    if (noMatchFound && searchQuery !== "") {
        document.querySelector('.shop-list').innerHTML = "<p>No results found.</p>";
    } else if (searchQuery === "") {
        // If the search input is cleared, show all items again
        document.querySelector('.shop-list').innerHTML = "";
        <?php foreach ($shop_data as $row): ?>
            var shopItem = document.createElement("div");
            shopItem.classList.add("shop-item");
            shopItem.setAttribute("data-shop-name", "<?= strtolower($row['shop_name']); ?>");

            shopItem.innerHTML = `
                <div class="shop-info"><strong>Shop Name:</strong> <?= htmlspecialchars($row['shop_name']) ?></div>
                <div class="shop-info"><strong>Owner Email:</strong> <?= htmlspecialchars($row['ownerEmail']) ?></div>
                <div class="shop-info"><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></div>
                <div class="shop-info"><strong>Number:</strong> <?= htmlspecialchars($row['number']) ?></div>
                <div class="shop-info"><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></div>
                <div class="shop-info"><strong>Branch:</strong> <?= htmlspecialchars($row['branch']) ?></div>
                <div class="shop-actions">
                    <button class="manage-button" data-id="<?= htmlspecialchars($row['id']) ?>" 
                            data-owneremail="<?= htmlspecialchars($row['ownerEmail']) ?>"
                            data-shopname="<?= htmlspecialchars($row['shop_name']) ?>"
                            data-email="<?= htmlspecialchars($row['email']) ?>"
                            data-number="<?= htmlspecialchars($row['number']) ?>"
                            data-address="<?= htmlspecialchars($row['address']) ?>"
                            data-branch="<?= htmlspecialchars($row['branch']) ?>"><i class='bx bxs-cog'></i> Manage</button>
                </div>
            `;
            document.querySelector('.shop-list').append(shopItem);
        <?php endforeach; ?>
        attachManageButtonEventListeners();
    }
});
</script>
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
