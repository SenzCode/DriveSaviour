<?php
session_start();
require '../navbar/navbar.php';
include_once('../../connection.php');

$orders_data = [];
$stmt = $conn->prepare("SELECT * FROM orders");
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders_data[] = $row;
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
            <input type="text" id="search" class="search-select" placeholder="Reference Number">
        </div>
        <br>
    </div>

    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Reference Number</th>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Purchase Date</th>
                    <th>Item Total</th>
                    <th>Total Price</th>
                    <th>Discount</th>
                    <th>Seller Income</th>
                    <th>Commission</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody id="orders-tbody">
                <?php foreach ($orders_data as $row): ?>
                    <tr>
                        <td data-cell="Reference Number"><?= htmlspecialchars($row['reference_number']) ?></td>
                        <td data-cell="Product ID"><?= htmlspecialchars($row['product_id']) ?></td>
                        <td data-cell="Quantity"><?= htmlspecialchars($row['quantity']) ?></td>
                        <td data-cell="Purchase Date"><?= htmlspecialchars($row['purchase_date']) ?></td>
                        <td data-cell="Item Total"><?= htmlspecialchars($row['item_total']) ?></td>
                        <td data-cell="Total Price"><?= htmlspecialchars($row['total_price']) ?></td>
                        <td data-cell="Discount"><?= htmlspecialchars($row['discount']) ?></td>
                        <td data-cell="Seller Income"><?= htmlspecialchars($row['seller_income']) ?></td>
                        <td data-cell="Commission"><?= htmlspecialchars($row['commission']) ?></td>
                        <td data-cell="Email"><?= htmlspecialchars($row['email']) ?></td>
                        <td data-cell="Status"><?= htmlspecialchars($row['status']) ?></td>
                        <td data-cell="Payment Status"><?= htmlspecialchars($row['payment_status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById("search").addEventListener("input", function() {
    var searchQuery = this.value.toLowerCase();
    var rows = document.querySelectorAll("#orders-tbody tr");
    rows.forEach(function(row) {
        var referenceNumber = row.querySelector("td[data-cell='Reference Number']").textContent.toLowerCase();
        if (referenceNumber.includes(searchQuery)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
</script>
</body>
</html>
