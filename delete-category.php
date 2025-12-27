<?php
include 'config.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

$id = $_GET['id'];
$conn->query("DELETE FROM categories WHERE id=$id");
header('Location: view-category.php');
?>
