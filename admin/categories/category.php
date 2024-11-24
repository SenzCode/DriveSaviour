<?php
require '../navbar/navbar.php';
require '../../connection.php';

$category_data = [];
$stmt = $conn->prepare("SELECT * FROM category");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $category_data[] = $row;
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
    <title>Category Management</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="../../shop_owner/shop/style.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php if ($message == 'insert'): ?>
        <div class="alert alert-success" id="alertMessage">The Category was created successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger" id="alertMessage">The Category was deleted successfully.</div>
    <?php elseif ($message == 'edit'): ?>
        <div class="alert alert-success" id="alertMessage">The Category was updated successfully.</div>
    <?php elseif ($message == 'error'): ?>
        <div class="alert alert-danger" id="alertMessage">Something went wrong: <?= htmlspecialchars($_GET['error'] ?? '') ?></div>
    <?php endif; ?>

    <div class="title">
        <h1>Category Management</h1>
        <br><br>
    </div>

    <form action="addcategory.php" method="POST">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="cat_name">Category Name:</label>
                    <input type="text" id="cat_name" name="cat_name" required>
                </div>
                <div class="form-group">
                    <label for="cdate">Created Date:</label>
                    <input type="date" id="cdate" name="cdate" required>
                </div>
            </div>
        </div>
        <button type="submit" name="action" value="insert" class="batch view-link">Add Category</button>
    </form>

    <div class="category-table">
        <table>
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($category_data as $category): ?>
                    <tr>
                        <td><?= htmlspecialchars($category['category_name']); ?></td>
                        <td><?= htmlspecialchars($category['created_date']); ?></td>
                        <td>
                            <button class="manage-button view-link"
                                data-id="<?= htmlspecialchars($category['id']) ?>"
                                data-category_name="<?= htmlspecialchars($category['category_name']) ?>"
                                data-created_date="<?= htmlspecialchars($category['created_date']) ?>">
                                Manage
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="manageCategoryModal" class="modal">
        <div class="modal-content">
            <span id="closeManageCategoryModal" class="close">&times;</span>
            <h2>Manage Category</h2>
            <form id="manageCategoryForm" action="category_manage.php" method="POST">
                <input type="hidden" id="manage_category_id" name="id">
                <div class="form-group">
                    <label for="manage_category_name">Category Name:</label>
                    <input type="text" id="manage_category_name" name="category_name" required>
                </div>
                <div class="form-group">
                    <label for="manage_created_date">Created Date:</label>
                    <input type="date" id="manage_created_date" name="created_date" required>
                </div>
                <br>
                <button type="submit" name="action" value="edit" class="batch view-link">Edit</button>
                <button type="submit" name="action" value="delete" class="batch delete-link">Delete</button>
            </form>
        </div>
    </div>

    <script>
        const manageButtons = document.querySelectorAll('.manage-button');
        const manageCategoryModal = document.getElementById('manageCategoryModal');
        const closeManageCategoryModal = document.getElementById('closeManageCategoryModal');
        const manageCategoryForm = document.getElementById('manageCategoryForm');

        manageButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const category = e.target.dataset;
                document.getElementById('manage_category_id').value = category.id;
                document.getElementById('manage_category_name').value = category.category_name;
                document.getElementById('manage_created_date').value = category.created_date;
                manageCategoryModal.style.display = 'block';
            });
        });

        closeManageCategoryModal.addEventListener('click', () => {
            manageCategoryModal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === manageCategoryModal) {
                manageCategoryModal.style.display = 'none';
            }
        });
    </script>

</body>

</html>