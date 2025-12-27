<?php
session_start();
include("../config.php");

$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();

// Remove item
if (isset($_GET['remove'])) {
    $cart_id = intval($_GET['remove']);
    $stmt = $conn->prepare("DELETE FROM cart WHERE id=?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    header("Location: viewCart.php");
    exit;
}

// Clear cart
if (isset($_GET['clear'])) {
    if ($user_id) {
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=?");
        $stmt->bind_param("i", $user_id);
    } else {
        $stmt = $conn->prepare("DELETE FROM cart WHERE session_id=?");
        $stmt->bind_param("s", $session_id);
    }
    $stmt->execute();
    header("Location: viewCart.php");
    exit;
}

// Update qty
if (isset($_POST['update_qty']) && !empty($_POST['qty'])) {
    foreach ($_POST['qty'] as $cart_id => $qty) {
        $qty = max(1, intval($qty));
        $stmt = $conn->prepare("UPDATE cart SET quantity=? WHERE id=?");
        $stmt->bind_param("ii", $qty, $cart_id);
        $stmt->execute();
    }
    header("Location: viewCart.php");
    exit;
}
?>
