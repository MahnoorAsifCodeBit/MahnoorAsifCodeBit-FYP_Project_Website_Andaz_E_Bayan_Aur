<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

include 'config.php';

$id = $_GET['id'];
$status = $_GET['status'];
$newStatus = $status == 1 ? 0 : 1;

$conn->query("UPDATE users SET status = $newStatus WHERE id = $id");

header("Location: user_list.php");
?>
