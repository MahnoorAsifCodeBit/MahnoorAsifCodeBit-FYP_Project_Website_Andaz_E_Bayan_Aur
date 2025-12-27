<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get challenge ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid Challenge ID'); window.location='admin_challenges.php';</script>";
    exit();
}

$challenge_id = $_GET['id'];

// Delete challenge
$delete_query = "DELETE FROM challenges WHERE id = $challenge_id";

if (mysqli_query($conn, $delete_query)) {
    echo "<script>alert('Challenge deleted successfully!'); window.location='admin_challenges.php';</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
