<?php
session_start();
require '../navbar/navbar.php';
include_once('../../connection.php');

$orders_data = [];

// Fetch data from orders table
$stmt_orders = $conn->prepare("SELECT o.*, p.* FROM orders o JOIN products p ON o.product_id = p.id");
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();
if ($result_orders) {
    while ($row = $result_orders->fetch_assoc()) {
        $orders_data[] = $row;
    }
}
$stmt_orders->close();

// Fetch data from mech_orders table
$stmt_mech_orders = $conn->prepare("SELECT mo.*, p.* FROM mech_orders mo JOIN products p ON mo.product_id = p.id");
$stmt_mech_orders->execute();
$result_mech_orders = $stmt_mech_orders->get_result();
if ($result_mech_orders) {
    while ($row = $result_mech_orders->fetch_assoc()) {
        $orders_data[] = $row;
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
        </div>
        <br>
        <div class="search-bar">
            <label for="date-filter">Select Date</label>
            <input type="date" id="date-filter">
        </div>
        <br>
    </div>

    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Purchase Date</th>
                    <th>Item Total</th>
                    <th>Discount</th>
                    <th>Seller Income</th>
                    <th>Commission</th>
                    <th>Seller Payment Status</th>
                </tr>
            </thead>
            <tbody id="orders-tbody">
                <?php if (!empty($orders_data)): ?>
                    <?php foreach ($orders_data as $row): ?>
                        <tr>
                            <td data-cell="Product Name"><?= htmlspecialchars($row['product_name']) ?></td>
                            <td data-cell="Product ID"><?= htmlspecialchars($row['product_id']) ?></td>
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
                        <td colspan="12">No results found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById("search").addEventListener("input", function() {
    var searchQuery = this.value.toLowerCase();
    var rows = document.querySelectorAll("#orders-tbody tr");
    rows.forEach(function(row) {
        var productName = row.querySelector("td[data-cell='Product Name']").textContent.toLowerCase();
        if (productName.includes(searchQuery)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});

document.getElementById("date-filter").addEventListener("input", function() {
    var selectedDate = this.value;
    var rows = document.querySelectorAll("#orders-tbody tr");
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
