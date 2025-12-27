<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get participant ID
if (!isset($_GET['participant_id']) || empty($_GET['participant_id'])) {
    echo "<script>alert('Invalid Participant ID'); window.location='admin_challenges.php';</script>";
    exit();
}

$participant_id = (int) $_GET['participant_id'];

// Delete participant entry
$delete_query = "DELETE FROM challenge_participants WHERE id = $participant_id";
if (mysqli_query($conn, $delete_query)) {
    echo "<script>alert('Participant removed successfully!'); window.history.back();</script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
