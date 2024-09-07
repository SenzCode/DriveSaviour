<?php
// Include database connection
include_once('../../connection.php');

// Check if form is submitted
if (isset($_POST['action']) && $_POST['action'] == 'insert') {
    // Get form data
    $shop_name = mysqli_real_escape_string($conn, $_POST['shop_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);

    // Insert data into the shops table
    $query = "INSERT INTO shops (shop_name, email, number, address, branch) VALUES ('$shop_name', '$email', '$number', '$address', '$branch')";
    if (mysqli_query($conn, $query)) {
        // Redirect with success message
        header('Location: manage_shops.php?message=insert');
    } else {
        // Handle insertion error
        echo "Error: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>
