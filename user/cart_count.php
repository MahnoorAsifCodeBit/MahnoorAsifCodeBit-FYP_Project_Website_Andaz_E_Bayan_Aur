<?php
session_start();
include("../config.php");
header('Content-Type: application/json; charset=utf-8');

$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();

if ($user_id) {
    $stmt = $conn->prepare("SELECT COALESCE(SUM(quantity),0) AS total FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $total = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
} else {
    $stmt = $conn->prepare("SELECT COALESCE(SUM(quantity),0) AS total FROM cart WHERE session_id = ?");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $total = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
}

echo json_encode(["count" => intval($total)]);
exit;
