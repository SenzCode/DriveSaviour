<?php
session_start();
require '../navbar/navbar.php';
include_once('../../connection.php');

$toBeReleased = [];
$released = [];

// Fetch data from orders table
$stmt_orders = $conn->prepare("SELECT o.*, p.* FROM orders o JOIN products p ON o.product_id = p.id");
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();
if ($result_orders) {
    while ($row = $result_orders->fetch_assoc()) {
        if ($row['payment_status'] === 'Pending') {
            $toBeReleased[] = $row;
        } elseif ($row['payment_status'] === 'Paid') {
            $released[] = $row;
        }
    }
}
$stmt_orders->close();

// Fetch data from mech_orders table
$stmt_mech_orders = $conn->prepare("SELECT mo.*, p.* FROM mech_orders mo JOIN products p ON mo.product_id = p.id");
$stmt_mech_orders->execute();
$result_mech_orders = $stmt_mech_orders->get_result();
if ($result_mech_orders) {
    while ($row = $result_mech_orders->fetch_assoc()) {
        if ($row['payment_status'] === 'Pending') {
            $toBeReleased[] = $row;
        } elseif ($row['payment_status'] === 'Paid') {
            $released[] = $row;
        }
    }
}
$stmt_mech_orders->close();

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission Manage</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="../Vehicle Owners/view_owners.css">

    <style>
        .date {
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 1em;
            margin-bottom: 10px;
            padding: 8px;
            width: 40%;
        }

        .tabs {
            display: flex;
            justify-content: center;
            margin-left: 150px;
            border-bottom: 2px solid #ddd;
            width: 100%;
        }

        .tab {
            padding: 10px 50px;
            text-align: center;
            cursor: pointer;
            font-weight: bold;
            border-bottom: 2px solid transparent;
        }

        .tab.active {
            border-bottom: 2px solid #007bff;
            color: #007bff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
<div class="main_container">
    <div class="title">
        <h1>Commission</h1>
        <br><br>
    </div>
    <div class="searchbars">
        <div class="search-bar">
            <label for="search">Search</label>
            <input type="text" id="search" class="search-select" placeholder="Product Name">

            <label for="date-filter">Select Date</label>
            <input type="date" id="date-filter" class="date">
        </div>
        <br>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <div class="tab active" onclick="showTab('to-be-released')">To Be Released</div>
        <div class="tab" onclick="showTab('released')">Released</div>
    </div>

    <!-- Tab Content -->
    <div id="to-be-released" class="tab-content active">
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Purchase Date</th>
                        <th>Item Total</th>
                        <th>Discount</th>
                        <th>Seller Income</th>
                        <th>Commission</th>
                        <th>Seller Payment Status</th>
                    </tr>
                </thead>
                <tbody id="to-be-released-tbody">
                    <?php if (!empty($toBeReleased)): ?>
                        <?php foreach ($toBeReleased as $row): ?>
                            <tr>
                                <td data-cell="Product Name"><?= htmlspecialchars($row['product_name']) ?></td>
                                <td data-cell="Quantity"><?= htmlspecialchars($row['quantity']) ?></td>
                                <td data-cell="Purchase Date"><?= htmlspecialchars($row['purchase_date']) ?></td>
                                <td data-cell="Item Total"><?= htmlspecialchars($row['item_total']) ?></td>
                                <td data-cell="Discount"><?= htmlspecialchars($row['discount']) ?></td>
                                <td data-cell="Seller Income"><?= htmlspecialchars($row['seller_income']) ?></td>
                                <td data-cell="Commission"><?= htmlspecialchars($row['commission']) ?></td>
                                <td data-cell="Payment Status"><?= htmlspecialchars($row['payment_status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No results found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="released" class="tab-content">
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Purchase Date</th>
                        <th>Item Total</th>
                        <th>Discount</th>
                        <th>Seller Income</th>
                        <th>Commission</th>
                        <th>Seller Payment Status</th>
                    </tr>
                </thead>
                <tbody id="released-tbody">
                    <?php if (!empty($released)): ?>
                        <?php foreach ($released as $row): ?>
                            <tr>
                                <td data-cell="Product Name"><?= htmlspecialchars($row['product_name']) ?></td>
                                <td data-cell="Quantity"><?= htmlspecialchars($row['quantity']) ?></td>
                                <td data-cell="Purchase Date"><?= htmlspecialchars($row['purchase_date']) ?></td>
                                <td data-cell="Item Total"><?= htmlspecialchars($row['item_total']) ?></td>
                                <td data-cell="Discount"><?= htmlspecialchars($row['discount']) ?></td>
                                <td data-cell="Seller Income"><?= htmlspecialchars($row['seller_income']) ?></td>
                                <td data-cell="Commission"><?= htmlspecialchars($row['commission']) ?></td>
                                <td data-cell="Payment Status"><?= htmlspecialchars($row['payment_status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No results found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function showTab(tabId) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

    // Remove active class from all tabs
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));

    // Show the selected tab content
    document.getElementById(tabId).classList.add('active');

    // Add active class to the clicked tab
    event.target.classList.add('active');
}

// Search filter
document.getElementById("search").addEventListener("input", function() {
    var searchQuery = this.value.toLowerCase();
    var rows = document.querySelectorAll(".tab-content.active tbody tr");
    rows.forEach(function(row) {
        var productName = row.querySelector("td[data-cell='Product Name']").textContent.toLowerCase();
        if (productName.includes(searchQuery)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});

// Date filter
document.getElementById("date-filter").addEventListener("input", function() {
    var selectedDate = this.value;
    var rows = document.querySelectorAll(".tab-content.active tbody tr");
    rows.forEach(function(row) {
        var purchaseDate = row.querySelector("td[data-cell='Purchase Date']").textContent;
        if (purchaseDate === selectedDate) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>
</body>
</html>
