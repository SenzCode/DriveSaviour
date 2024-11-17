<?php
require '../navbar/nav.php';
require '../../connection.php';

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit();
}

$loggedInOwnerEmail = $_SESSION['email'];

$orders_data = [];

try {
    $stmt_orders = $conn->prepare("SELECT o.*, p.product_name, p.shop_id, s.shop_name 
                                   FROM orders o 
                                   JOIN products p ON o.product_id = p.id 
                                   JOIN shops s ON p.shop_id = s.id 
                                   WHERE s.ownerEmail = ?");
    $stmt_orders->bind_param("s", $loggedInOwnerEmail);
    $stmt_orders->execute();
    $result_orders = $stmt_orders->get_result();

    while ($row = $result_orders->fetch_assoc()) {
        $orders_data[] = $row;
    }
    $stmt_orders->close();

    $stmt_mech_orders = $conn->prepare("SELECT mo.*, p.product_name, p.shop_id, s.shop_name 
                                        FROM mech_orders mo 
                                        JOIN products p ON mo.product_id = p.id 
                                        JOIN shops s ON p.shop_id = s.id 
                                        WHERE s.ownerEmail = ?");
    $stmt_mech_orders->bind_param("s", $loggedInOwnerEmail);
    $stmt_mech_orders->execute();
    $result_mech_orders = $stmt_mech_orders->get_result();

    while ($row = $result_mech_orders->fetch_assoc()) {
        $orders_data[] = $row;
    }
    $stmt_mech_orders->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income</title>
    <link rel="stylesheet" href="../shop/style.css">
    <link rel="stylesheet" href="../navbar/style.css">
    <style>
        .title{
            margin-top: 20px;
        }
        marquee{
            margin-top: -50px;
        }
    </style>
</head>
<body>
    <div class="main_container">
        <marquee behavior="scroll" direction="right" scrollamount="5" style="color: red; font-size: 20px; font-weight: bold;">All seller payments are paid every Friday.</marquee>
        <h2 class="title">Payment Data</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Reference Number</th>
                    <th>Product Name</th>
                    <th>Purchase Date</th>
                    <th>Quantity</th>
                    <th>Seller Income</th>
                    <th>Income Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders_data)): ?>
                    <?php foreach ($orders_data as $order): ?>
                        <tr>
                            <td data-cell="Reference Number"><?php echo htmlspecialchars($order['reference_number']); ?></td>
                            <td data-cell="Product Name"><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td data-cell="Purchase Date"><?php echo htmlspecialchars($order['purchase_date']); ?></td>
                            <td data-cell="Quantity"><?php echo htmlspecialchars($order['quantity']); ?></td>
                            <td data-cell="Seller Income"><?php echo htmlspecialchars($order['seller_income']); ?></td>
                            <td data-cell="Status"><?php echo htmlspecialchars($order['payment_status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="alert alert-danger">No orders found for your shop.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
