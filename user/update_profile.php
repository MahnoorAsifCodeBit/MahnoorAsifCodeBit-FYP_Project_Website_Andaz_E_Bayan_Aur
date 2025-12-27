<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../userLogin.php");
    exit();
}
require '../config.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $bio = $_POST['bio'];

    // Handle profile picture upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = basename($_FILES['profile_pic']['name']);
        $targetFilePath = $targetDir . time() . "_" . $fileName;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFilePath);
    } else {
        // If no new image uploaded, keep the old one
        $fetch = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
        $fetch->bind_param("i", $user_id);
        $fetch->execute();
        $result = $fetch->get_result()->fetch_assoc();
        $targetFilePath = $result['profile_pic'];
    }

    // ✅ Update query with bio field included
    $update = $conn->prepare("UPDATE users SET name = ?, email = ?, contact = ?, bio = ?, profile_pic = ? WHERE id = ?");
    $update->bind_param("sssssi", $name, $email, $contact, $bio, $targetFilePath, $user_id);

    if ($update->execute()) {
        header("Location: viewProfile.php?success=1");
        exit();
    } else {
        echo "Error updating profile.";
    }
}
?>
