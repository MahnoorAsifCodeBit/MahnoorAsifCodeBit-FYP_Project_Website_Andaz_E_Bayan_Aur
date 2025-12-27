<?php
include '../config.php';
session_start();

if (!empty($_SESSION['user_id']) && !empty($_GET['poetry_id'])) {
    $user_id = (int)$_SESSION['user_id'];
    $poetry_id = (int)$_GET['poetry_id'];
    $now = date('Y-m-d H:i:s');

    $sql = "INSERT INTO click_logs (user_id, click_type, related_id, click_time) 
            VALUES ($user_id, 'poetry', $poetry_id, '$now')";

    if (!$conn->query($sql)) {
        die("SQL Error: " . $conn->error);
    }

    header("Location: poetry_detail.php?id=$poetry_id");
    exit();
} else {
    echo "Poetry ID missing or user not logged in!";
}
