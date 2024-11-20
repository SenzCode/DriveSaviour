<?php
require '../../connection.php'; // Adjust the path to your connection file

header('Content-Type: application/json'); // Set response type to JSON

if (isset($_GET['month']) && is_numeric($_GET['month'])) {
    $month = (int)$_GET['month']; // Sanitize the month parameter
    $year = date("Y"); // Use the current year by default. Adjust if needed.

    // Query to retrieve order counts and total commission grouped by date for the selected month
    $query = "SELECT DATE(purchase_date) AS order_date, 
                     COUNT(*) AS order_count, 
                     SUM(commission) AS total_commission 
              FROM orders 
              WHERE MONTH(purchase_date) = ? AND YEAR(purchase_date) = ? 
              GROUP BY DATE(purchase_date) 
              ORDER BY order_date";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $month, $year); // Bind month and year
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch data
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                "order_date" => $row["order_date"],
                "order_count" => (int)$row["order_count"], // Convert to integer for consistency
                "total_commission" => (float)$row["total_commission"] // Convert to float for consistency
            ];
        }

        // Return data as JSON
        echo json_encode($data);
    } else {
        // Error in preparing the query
        echo json_encode(["error" => "Failed to prepare query"]);
    }
} else {
    // Missing or invalid month parameter
    echo json_encode(["error" => "Invalid or missing month parameter"]);
}
?>
