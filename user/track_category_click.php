<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../userLogin.php");
    exit();
}

if (!empty($_SESSION['user_id']) && !empty($_GET['category_id'])) {
    $user_id = (int)$_SESSION['user_id'];
    $category_id = (int)$_GET['category_id']; // Now correctly using GET
    $now = date('Y-m-d H:i:s');

    $sql = "INSERT INTO click_logs (user_id, click_type, related_id, click_time) 
            VALUES ($user_id, 'category', $category_id, '$now')";

    if (!$conn->query($sql)) {
        die("SQL Error: " . $conn->error);
    }

    // After tracking, send them to the category_poetry.php page
    header("Location: category_poetry.php?category_id=$category_id");
    exit();
} else {
    echo "Category ID missing or user not logged in!";
}
