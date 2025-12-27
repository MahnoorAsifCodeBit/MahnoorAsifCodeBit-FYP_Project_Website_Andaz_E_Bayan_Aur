<?php
session_start();
ob_start();
header('Content-Type: application/json; charset=utf-8');

// hide warnings in output (but still log)
ini_set('display_errors', 0);
ini_set('log_errors', 1);

include("../config.php"); // keep same path as your e-commerce.php

try {
    $session_id = session_id();
    $user_id = $_SESSION['user_id'] ?? null;

    if (!isset($_POST['product_id'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid request."]);
        exit;
    }

    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity'] ?? 1));

    if ($product_id <= 0) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid product id."]);
        exit;
    }

    // validate product exists
    $pstmt = $conn->prepare("SELECT id FROM products WHERE id = ? LIMIT 1");
    $pstmt->bind_param("i", $product_id);
    $pstmt->execute();
    $presult = $pstmt->get_result();
    if ($presult->num_rows === 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Product not found."]);
        exit;
    }

    if ($user_id) {
        // logged-in: search by user_id + product_id
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? LIMIT 1");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $newQty = $row['quantity'] + $quantity;
            $up = $conn->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ?");
            $up->bind_param("ii", $newQty, $row['id']);
            if (!$up->execute()) throw new Exception($conn->error);
        } else {
            $ins = $conn->prepare("INSERT INTO cart (user_id, session_id, product_id, quantity) VALUES (?, ?, ?, ?)");
            $ins->bind_param("isii", $user_id, $session_id, $product_id, $quantity);
            if (!$ins->execute()) throw new Exception($conn->error);
        }

        // return updated count for this user
        $cnt = $conn->prepare("SELECT COALESCE(SUM(quantity),0) AS total FROM cart WHERE user_id = ?");
        $cnt->bind_param("i", $user_id);
        $cnt->execute();
        $countRow = $cnt->get_result()->fetch_assoc();
        $totalCount = intval($countRow['total'] ?? 0);

    } else {
        // guest: keep cart tied to session_id
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE session_id = ? AND product_id = ? LIMIT 1");
        $stmt->bind_param("si", $session_id, $product_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $newQty = $row['quantity'] + $quantity;
            $up = $conn->prepare("UPDATE cart SET quantity = ?, updated_at = NOW() WHERE id = ?");
            $up->bind_param("ii", $newQty, $row['id']);
            if (!$up->execute()) throw new Exception($conn->error);
        } else {
            $ins = $conn->prepare("INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)");
            $ins->bind_param("sii", $session_id, $product_id, $quantity);
            if (!$ins->execute()) throw new Exception($conn->error);
        }

        // return updated count for this session
        $cnt = $conn->prepare("SELECT COALESCE(SUM(quantity),0) AS total FROM cart WHERE session_id = ?");
        $cnt->bind_param("s", $session_id);
        $cnt->execute();
        $countRow = $cnt->get_result()->fetch_assoc();
        $totalCount = intval($countRow['total'] ?? 0);
    }

    // clear any accidental output
    ob_end_clean();
    echo json_encode(["success" => true, "message" => "Product added to cart!", "count" => $totalCount]);
    exit;
}
catch (Exception $e) {
    error_log("add_to_cart.php error: ".$e->getMessage());
    if (ob_get_length()) ob_end_clean();
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Server error — try again."]);
    exit;
}
