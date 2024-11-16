<?php
require '../../connection.php'; // Adjust the path to your connection file

header('Content-Type: application/json'); // Set response type to JSON

if (isset($_GET['month']) && is_numeric($_GET['month'])) {
    $month = (int)$_GET['month']; // Sanitize the month parameter
    $year = date("Y"); // Use the current year by default. Adjust if needed.

    // Query to retrieve order counts grouped by date for the selected month
    $query = "SELECT DATE(purchase_date) AS order_date, COUNT(*) AS order_count 
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
            $data[] = $row;
        }

        // Return data as JSON
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "Failed to prepare query"]);
    }
} else {
    echo json_encode(["error" => "Invalid or missing month parameter"]);
}
?>
