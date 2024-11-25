<?php
require '../../connection.php';
session_start();

header('Content-Type: application/json');

if (isset($_SESSION['email']) && isset($_GET['month']) && is_numeric($_GET['month'])) {
    $user_email = $_SESSION['email'];
    $month = (int)$_GET['month'];
    $year = date("Y");

    $query = "
        SELECT DATE(o.purchase_date) AS order_date, 
               SUM(o.seller_income) AS total_seller_income
        FROM orders o
        INNER JOIN products p ON o.product_id = p.id
        INNER JOIN shops s ON p.shop_id = s.id
        WHERE MONTH(o.purchase_date) = ?
          AND YEAR(o.purchase_date) = ?
          AND s.ownerEmail = ?
        GROUP BY DATE(o.purchase_date)
        
        UNION ALL
        
        SELECT DATE(mo.purchase_date) AS order_date, 
               SUM(mo.seller_income) AS total_seller_income
        FROM mech_orders mo
        INNER JOIN products p ON mo.product_id = p.id
        INNER JOIN shops s ON p.shop_id = s.id
        WHERE MONTH(mo.purchase_date) = ?
          AND YEAR(mo.purchase_date) = ?
          AND s.ownerEmail = ?
        GROUP BY DATE(mo.purchase_date)
        
        ORDER BY order_date";

    if ($stmt = $conn->prepare($query)) {
        // Bind month, year, and user email for both sections of the UNION query
        $stmt->bind_param("iisiis", $month, $year, $user_email, $month, $year, $user_email);
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
