<?php
require '../../connection.php';

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'edit') {
    $categoryId = $_POST['id'];
    $categoryName = $_POST['category_name'];
    $description = $_POST['description'];
    $createdDate = $_POST['created_date'];

    $stmt = $conn->prepare("UPDATE category SET category_name = ?, description = ?, created_date = ? WHERE id = ?");
    $stmt->bind_param("sssi", $categoryName, $description, $createdDate, $categoryId);

    if ($stmt->execute()) {
        header("Location: category.php?message=edit");
    } else {
        header("Location: category.php?message=error&error=" . urlencode($stmt->error));
    }
    $stmt->close();
} elseif ($action === 'delete') {
    $categoryId = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM category WHERE id = ?");
    $stmt->bind_param("i", $categoryId);

    if ($stmt->execute()) {
        header("Location: category.php?message=delete");
    } else {
        header("Location: category.php?message=error&error=" . urlencode($stmt->error));
    }
    $stmt->close();
} else {
    header("Location: category.php?message=error&error=Invalid action");
}

$conn->close();
?>
