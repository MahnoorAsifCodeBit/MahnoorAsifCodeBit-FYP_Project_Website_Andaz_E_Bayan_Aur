<?php
include 'config.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];


if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Delete poetry from the table
    $sql = "DELETE FROM poetry_content WHERE content_id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: view_poetry.php?msg=Poetry deleted successfully");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
