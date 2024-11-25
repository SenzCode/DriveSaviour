<?php
require '../navbar/nav.php';
require '../../connection.php';

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit();
}

$loggedInOwnerEmail = $_SESSION['email'];

$incomeToBeReceived = [];
$incomeReceived = [];

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
        if ($row['payment_status'] === 'Pending') {
            $incomeToBeReceived[] = $row;
        } elseif ($row['payment_status'] === 'paid') {
            $incomeReceived[] = $row;
        }
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
        if ($row['payment_status'] === 'Pending') {
            $incomeToBeReceived[] = $row;
        } elseif ($row['payment_status'] === 'paid') {
            $incomeReceived[] = $row;
        }
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
        .tabs {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            border-bottom: 2px solid #ddd;
        }

        .tab {
            padding: 10px 20px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        .no-data {
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="main_container">
        <marquee behavior="scroll" direction="right" scrollamount="5" style="color: red; font-size: 20px; font-weight: bold;">All seller payments are paid every Friday.</marquee>
        <h2 class="title">Payment Data</h2>

        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" onclick="showTab('income-to-be-received')">Income to be Received</div>
            <div class="tab" onclick="showTab('income-received')">Income Received</div>
            <div class="tab" onclick="showTab('visual-representation')">Visual Representation</div>
        </div>

        <!-- Tab Content -->
        <div id="income-to-be-received" class="tab-content active">
            <?php if (!empty($incomeToBeReceived)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Reference Number</th>
                            <th>Product Name</th>
                            <th>Purchase Date</th>
                            <th>Quantity</th>
                            <th>Seller Income</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($incomeToBeReceived as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['reference_number']); ?></td>
                                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['purchase_date']); ?></td>
                                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($order['seller_income']); ?></td>
                                <td><?php echo htmlspecialchars($order['payment_status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">No income to be received.</p>
            <?php endif; ?>
        </div>

        <div id="income-received" class="tab-content">
            <?php if (!empty($incomeReceived)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Reference Number</th>
                            <th>Product Name</th>
                            <th>Purchase Date</th>
                            <th>Quantity</th>
                            <th>Seller Income</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($incomeReceived as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['reference_number']); ?></td>
                                <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['purchase_date']); ?></td>
                                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($order['seller_income']); ?></td>
                                <td><?php echo htmlspecialchars($order['payment_status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">No income received.</p>
            <?php endif; ?>
        </div>

        <div id="visual-representation" class="tab-content">
            <?php
                require '../chart/orderslinechart.php';
            ?>
            <?php
                require '../chart/trendingproducts.php';
            ?>
        </div>

    </div>

    <script>
        function showTab(tabId) {
            // Hide all tab content
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

            // Remove active class from all tabs
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));

            // Show the selected tab content
            document.getElementById(tabId).classList.add('active');

            // Add active class to the selected tab
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
