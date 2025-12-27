<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];


include 'config.php';

$id = $_GET['id'];
$conn->query("DELETE FROM users WHERE id = $id");

header("Location: user_list.php");
?>
