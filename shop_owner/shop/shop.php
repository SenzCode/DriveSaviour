<?php
require '../navbar/nav.php';
require '../../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shop_name = htmlspecialchars($_POST['shop_name']);
    $email = htmlspecialchars($_POST['email']);
    $number = htmlspecialchars($_POST['number']);
    $address = htmlspecialchars($_POST['address']);
    $branch = htmlspecialchars($_POST['branch']);

    $sql = "INSERT INTO shops (shop_name, email, number, address, branch) 
            VALUES (?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssss", $shop_name, $email, $number, $address, $branch);

        if ($stmt->execute()) {
            $success_message = "Shop added successfully!";
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

// Fetch all shop records
$sql = "SELECT * FROM shops";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Management</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../navbar/style.css">
</head>
<body>
<div class="main_container">

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?= $success_message; ?></div>
    <?php elseif (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message; ?></div>
    <?php endif; ?>

    <br>

    <!-- Shop creation form -->
    <form action="" method="POST">
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
                    <label for="number">Contact Number:</label>
                    <input type="text" id="number" name="number" required>
                </div>

                <div class="form-group">
                    <label for="branch">Branch:</label>
                    <input type="text" id="branch" name="branch" required>
                </div>

            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>
            </div>
        </div>
        <br>
        <button type="submit" class="batch view-link">Add Shop</button>
    </form>

    <!-- Shop listing -->
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Shop Name</th>
                    <th>Email</th>
                    <th>Number</th>
                    <th>Address</th>
                    <th>Branch</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']); ?></td>
                            <td><?= htmlspecialchars($row['shop_name']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['number']); ?></td>
                            <td><?= htmlspecialchars($row['address']); ?></td>
                            <td><?= htmlspecialchars($row['branch']); ?></td>
                            <td>
                                <button class="btn" onclick="window.location.href='edit_shop.php?id=<?= $row['id']; ?>'">Edit</button>
                                <button class="btn" onclick="window.location.href='delete_shop.php?id=<?= $row['id']; ?>'">Delete</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No shops found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>



    <!-- manage modal -->
    <div id="manageBatchModal" class="modal">
        <div class="modal-content">
            <span id="closeManageBatchModal" class="close">&times;</span>
            <h2>Manage Shop</h2>
            <form id="manageBatchForm" action="batch_manage.php" method="POST">
                <input type="hidden" id="manage_id" name="id">
                <div class="form-group">
                    <label for="manage_shop_name">Shop Name:</label>
                    <input type="text" id="manage_shop_name" name="shop_name" required>
                </div>
                <div class="form-group">
                    <label for="manage_email">Email:</label>
                    <input type="email" id="manage_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="manage_number">Contact Number:</label>
                    <input type="text" id="manage_number" name="number" required>
                </div>
                <div class="form-group">
                    <label for="manage_branch">Branch:</label>
                    <input type="text" id="manage_branch" name="branch" required>
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
                var shopName = this.dataset.shopName;
                var email = this.dataset.email;
                var number = this.dataset.number;
                var address = this.dataset.address;
                var branch = this.dataset.branch;

                document.getElementById('manage_id').value = shopId;
                document.getElementById('manage_shop_name').value = shopName;
                document.getElementById('manage_email').value = email;
                document.getElementById('manage_number').value = number;
                document.getElementById('manage_address').value = address;
                document.getElementById('manage_branch').value = branch;

                manageBatchModal.style.display = "block";
            });
        });

        // Confirm deletion for Shop
        document.getElementById("manageBatchForm").addEventListener("submit", function(event) {
            var action = document.activeElement.value;
            if (action === 'delete') {
                var confirmDelete = confirm("Are you sure to delete this shop?");
                if (!confirmDelete) {
                    event.preventDefault();
                }
            }
        });

        // Search functionality for Shop ID
        document.getElementById("search-icon").addEventListener("click", function() {
            var searchValue = document.getElementById("search").value.toLowerCase();
            var tableRows = document.querySelectorAll("#course-tbody tr");

            tableRows.forEach(function(row) {
                var shopIdCell = row.querySelector("[data-id]").innerText.toLowerCase();
                if (searchValue === "" || shopIdCell.includes(searchValue)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
</script>
</body>
</html>

<?php
$conn->close();
?>