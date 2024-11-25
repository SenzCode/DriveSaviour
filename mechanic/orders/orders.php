<?php
session_start();
require '../../connection.php';
require '../navbar/nav.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

// Initialize variables
$userEmail = $_SESSION['email'];
$filterDate = isset($_GET['purchase_date']) ? $_GET['purchase_date'] : null;

// Prepare the SQL query
$query = "SELECT o.*, p.product_name, p.image_url 
          FROM mech_orders o 
          JOIN products p ON o.product_id = p.id 
          WHERE o.email = ?";
if ($filterDate) {
    $query .= " AND o.purchase_date = ?";
}

$stmt = $conn->prepare($query);
if ($filterDate) {
    $stmt->bind_param("ss", $userEmail, $filterDate);
} else {
    $stmt->bind_param("s", $userEmail);
}

$stmt->execute();
$result = $stmt->get_result();

// Group orders by status
$groupedOrders = [
    'Pending' => [],
    'Ready to Pick' => [],
    'Completed' => [],
    'Cancelled' => [],
];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $status = $row['status'] ?? 'Unknown';
        $groupedOrders[$status][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Tabs */
        .tabs {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
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
            margin-top: 20px;
        }
        /* Order Items */
        .order-item {
            display: flex;
            gap: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .order-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }
        .order-details {
            flex: 1;
        }
        .rate-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .rate-btn:hover {
            background-color: #0056b3;
        }
        /* Date Filter */
        .date {
            margin: 20px 0;
        }
        .date input {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="main_container">
        <!-- Date Filter -->
        <form method="get" class="date">
            <label for="purchase_date">Select a date:</label>
            <input type="date" name="purchase_date" id="purchase_date" value="<?= htmlspecialchars($filterDate) ?>">
            <button type="submit" class="search"><i class="bx bx-search"></i></button>
        </form>

        <!-- Orders Header -->
        <div class="order-header">
            <button class="back-btn" onclick="window.location.href='../products/product.php'">&larr; Back</button>
            <h1>Your Orders</h1>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" onclick="showTab('pending')">Pending</div>
            <div class="tab" onclick="showTab('ready_to_pick')">Ready to Pick</div>
            <div class="tab" onclick="showTab('completed')">Completed</div>
            <div class="tab" onclick="showTab('cancelled')">Cancelled</div>
        </div>

        <!-- Tab Content -->
        <?php foreach ($groupedOrders as $status => $orders): ?>
            <div id="<?= strtolower(str_replace(' ', '_', $status)) ?>" class="tab-content <?= $status === 'Pending' ? 'active' : '' ?>">
                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="order-item">
                            <img src="<?= htmlspecialchars($order['image_url']) ?>" alt="<?= htmlspecialchars($order['product_name']) ?>">
                            <div class="order-details">
                                <h3><?= htmlspecialchars($order['product_name']) ?></h3>
                                <div>Order Reference: <?= htmlspecialchars($order['reference_number']) ?></div>
                                <div>Quantity: <?= htmlspecialchars($order['quantity']) ?></div>
                                <div>Purchase Date: <?= htmlspecialchars($order['purchase_date']) ?></div>
                                <div>Item Total: Rs. <?= htmlspecialchars($order['item_total']) ?></div>
                                <div>Total Price: Rs. <?= htmlspecialchars($order['total_price']) ?></div>
                                <div>Discount: Rs. <?= htmlspecialchars($order['discount']) ?></div>
                                <div>Status: <?= htmlspecialchars($order['status']) ?></div>
                                <button class="rate-btn" onclick="window.location.href='../ratings/rate_product.php?product_id=<?= htmlspecialchars($order['product_id']) ?>'">Rate Product</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No <?= htmlspecialchars($status) ?> orders.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php require '../footer/footer.php'; ?>

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
