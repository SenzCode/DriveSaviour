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
    
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin-left: 300px;
            margin-top: 100px;
        }

        .title h1 {
            font-size: 2em;
            color: #333;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 1em;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .form-container {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .batch {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .view-link:hover,
        .delete-link:hover {
            background-color: #45a049;
        }

        .delete-link {
            background-color: #f44336;
        }

        .category-table {
            width: 100%;
            overflow-x: auto;
        }

        .category-table table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 1em;
        }

        .category-table th,
        .category-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .category-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            body {
                margin: 20px;
            }

            .title h1 {
                font-size: 1.5em;
            }

            .form-row {
                flex-direction: column;
            }

            .batch {
                font-size: 0.9em;
                padding: 8px 16px;
            }

            .category-table table,
            .category-table thead,
            .category-table tbody,
            .category-table th,
            .category-table td,
            .category-table tr {
                display: block;
                width: 100%;
            }

            .category-table tr {
                margin-bottom: 15px;
                border-bottom: 1px solid #ddd;
                padding-bottom: 10px;
            }

            .category-table th,
            .category-table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            .category-table th::before,
            .category-table td::before {
                position: absolute;
                left: 15px;
                text-align: left;
                font-weight: bold;
            }

            .category-table td:nth-of-type(1)::before {
                content: "ID";
            }

            .category-table td:nth-of-type(2)::before {
                content: "Category Name";
            }

            .category-table td:nth-of-type(3)::before {
                content: "Description";
            }

            .category-table td:nth-of-type(4)::before {
                content: "Date";
            }

            .category-table td:nth-of-type(5)::before {
                content: "Action";
            }

            .category-table thead {
                display: none;
            }
        }
    </style>
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
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($category_data as $category): ?>
                    <tr>
                        <td><?= htmlspecialchars($category['id']); ?></td>
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
