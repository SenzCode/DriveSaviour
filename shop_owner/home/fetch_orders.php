<?php
require '../../connection.php'; // Adjust the path to your connection file
session_start(); // Start the session to access the logged-in user's data

header('Content-Type: application/json'); // Set response type to JSON

if (isset($_SESSION['email']) && isset($_GET['month']) && is_numeric($_GET['month'])) {
    $user_email = $_SESSION['email']; // Get the logged-in user's email
    $month = (int)$_GET['month']; // Sanitize the month parameter
    $year = date("Y"); // Use the current year by default. Adjust if needed.

    // Query to fetch seller income by date
    $query = "SELECT DATE(o.purchase_date) AS order_date, 
                     SUM(o.seller_income) AS total_seller_income
              FROM orders o
              INNER JOIN products p ON o.product_id = p.id
              INNER JOIN shops s ON p.shop_id = s.id
              WHERE MONTH(o.purchase_date) = ?
                AND YEAR(o.purchase_date) = ?
                AND s.ownerEmail = ?
              GROUP BY DATE(o.purchase_date)
              ORDER BY order_date";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("iis", $month, $year, $user_email); // Bind month, year, and user email
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch data
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        // Return data as JSON
        echo json_encode($data);
    } else {
        // Debugging: output the SQL error
        echo json_encode(["error" => "Failed to prepare query", "sql_error" => $conn->error]);
    }
} else {
    echo json_encode(["error" => "Invalid or missing parameters, or user not logged in"]);
}
?>
