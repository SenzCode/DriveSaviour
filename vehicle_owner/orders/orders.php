<?php
session_start();
require '../../connection.php';
require '../navbar/nav.php';

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit;
}

$userEmail = $_SESSION['email'];

// Initialize the filter condition
$filterDate = isset($_GET['purchase_date']) ? $_GET['purchase_date'] : null;

// Prepare the query based on whether a date is provided
$query = "SELECT o.*, p.product_name, p.image_url 
          FROM orders o 
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
    <link rel="stylesheet" href="../navbar/style.css">
    <link rel="stylesheet" href="style.css">
    <title>Your Orders</title>
    <style>
        /* Tab Styles */
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
    </style>
</head>
<body>
    <div class="main_container">
        <form method="get" class="date">
            <label for="purchase_date">Select a date:</label>
            <input type="date" name="purchase_date" id="purchase_date" value="<?= htmlspecialchars($filterDate) ?>">
            <button type="submit" class="search"><i class="bx bx-search"></i></button>
        </form>
        <div class="order-header">
            <button class="back-btn" onclick="window.location.href='../products/product.php'">&larr; Back</button>
            <h1>Your Orders</h1>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <div class="tab active" onclick="showTab('pending')">Pending</div>
            <div class="tab" onclick="showTab('ready')">Ready to Pick</div>
            <div class="tab" onclick="showTab('completed')">Completed</div>
            <div class="tab" onclick="showTab('cancelled')">Cancelled</div>
        </div>

        <!-- Tab Content -->
        <div id="pending" class="tab-content active">
            <?php if (count($groupedOrders['Pending']) > 0): ?>
                <?php foreach ($groupedOrders['Pending'] as $order): ?>
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
                            <button class="rate-btn" onclick="window.location.href='../ratings/rate_product.php?product_id=<?= $order['product_id'] ?>'">Rate Product</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No Pending orders.</p>
            <?php endif; ?>
        </div>

        <div id="ready" class="tab-content">
            <?php if (count($groupedOrders['Ready to Pick']) > 0): ?>
                <?php foreach ($groupedOrders['Ready to Pick'] as $order): ?>
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
                            <button class="rate-btn" onclick="window.location.href='../ratings/rate_product.php?product_id=<?= $order['product_id'] ?>'">Rate Product</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No Ready to Pick orders.</p>
            <?php endif; ?>
        </div>

        <div id="completed" class="tab-content">
            <?php if (count($groupedOrders['Completed']) > 0): ?>
                <?php foreach ($groupedOrders['Completed'] as $order): ?>
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
                            <button class="rate-btn" onclick="window.location.href='../ratings/rate_product.php?product_id=<?= $order['product_id'] ?>'">Rate Product</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No Completed orders.</p>
            <?php endif; ?>
        </div>

        <div id="cancelled" class="tab-content">
            <?php if (count($groupedOrders['Cancelled']) > 0): ?>
                <?php foreach ($groupedOrders['Cancelled'] as $order): ?>
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
                            <button class="rate-btn" onclick="window.location.href='../ratings/rate_product.php?product_id=<?= $order['product_id'] ?>'">Rate Product</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No Cancelled orders.</p>
            <?php endif; ?>
        </div>
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