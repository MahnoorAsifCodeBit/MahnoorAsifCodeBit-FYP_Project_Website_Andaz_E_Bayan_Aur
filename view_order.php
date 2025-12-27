<?php
session_start();
include("config.php");


if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];




$order_id = $_GET['id'] ?? 0;

// Fetch order info
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();
$stmt->close();

// Fetch order items with product names
$stmt_items = $conn->prepare("
    SELECT oi.product_id, oi.quantity, oi.price, p.name AS product_name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();
$stmt_items->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Order | Andaz-e-Bayan Aur</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    body {
        background: #f3f1f0;
        font-family: 'Poppins', sans-serif;
    }
    .order-container {
        max-width: 900px;
        margin: 120px auto;
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    h2 {
        color: #800000;
        font-weight: 600;
        margin-bottom: 20px;
    }
    h4 {
        color: #800000;
        margin-top: 30px;
        margin-bottom: 15px;
    }
    .order-details p {
        font-weight: 500;
        margin-bottom: 8px;
    }
    table {
        border-radius: 8px;
        overflow: hidden;
    }
    thead {
        background-color: #800000;
        color: #fff;
    }
    tbody tr {
        transition: all 0.3s ease;
    }
    tbody tr:hover {
        background-color: #f9e6e6;
    }
    td, th {
        vertical-align: middle !important;
    }
    .btn-back {
        background-color: #800000;
        border-color: #800000;
        color: #fff;
    }
    .btn-back:hover {
        background-color: #a30000;
        border-color: #a30000;
        color: #fff;
    }
             /* Sidebar (same as before) */
    .sidebar {
      width: 260px;
      background: #1a1d2b;
      color: #fff;
      position: fixed;
      top: 0; bottom: 0; left: 0;
      padding: 20px 0;
      display: flex;
      flex-direction: column;
      box-shadow: 4px 0 20px rgba(0,0,0,0.1);
      z-index: 100;
    }
    .sidebar h2 {
      text-align: center;
      font-size: 1.5rem;
      margin-bottom: 30px;
      font-weight: 700;
      color: #fff;
    }
    .sidebar a {
      padding: 14px 25px;
      display: block;
      color: #bbb;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .sidebar a:hover, .sidebar a.active {
      background: rgba(255,255,255,0.1);
      color: #fff;
    }

    /* Main content area */
    .main-content {
      margin-left: 260px;
      padding: 20px;
    }

    /* Topbar */
    .topbar {
      background: #fff;
      padding: 15px 25px;
      border-radius: 12px;
      margin-bottom: 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php"><i class="fa fa-home me-2"></i>Dashboard</a>
    <a href="add-category.php"><i class="fa fa-plus me-2"></i>Add Category</a>
    <a href="view-category.php"><i class="fa fa-list me-2"></i>View Categories</a>
    <a href="add_poetry.php"><i class="fa fa-pen me-2"></i>Add Poetry</a>
    <a href="view_poetry.php"><i class="fa fa-book me-2"></i>View Poetry</a>
    <a href="user_list.php"><i class="fa fa-users me-2"></i>Manage Users</a>
    <a href="analytics.php"><i class="fa fa-chart-line me-2"></i>Analytics</a>
    <a href="admin_add_challenge.php"><i class="fa fa-swords me-2"></i>Add Challenges</a>
    <a href="admin_challenges.php"><i class="fa fa-flag me-2"></i>View Challenges</a>
    <a href="orders.php"><i class="fa fa-box me-2"></i>Orders</a>
    <a href="admin_products.php"><i class="fa fa-shopping-cart me-2"></i>Calligraphy Products</a>
    <a href="change-password.php"><i class="fa fa-key me-2"></i>Change Password</a>
    <a href="logout.php"><i class="fa fa-sign-out me-2"></i>Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">

    <!-- Topbar -->
    <div class="topbar">
      <h5 class="mb-0">Welcome, <?php echo htmlspecialchars($admin_name); ?></h5>
      <div>
        <a href="dashboard.php" class="btn btn-sm btn-maroon me-2"><i class="fas fa-home"></i> Home</a>
        <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>
<div class="container order-container">
  <h2>Order #<?= $order['id']; ?> Details</h2>
  <div class="order-details">
    <p><strong>Customer:</strong> <?= htmlspecialchars($order['customer_name']); ?></p>
    <p><strong>Phone:</strong> <?= htmlspecialchars($order['phone']); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($order['email']); ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($order['address']); ?></p>
    <p><strong>Status:</strong> <?= $order['status']; ?></p>
    <p><strong>Total:</strong> Rs <?= number_format($order['total_amount'], 2); ?></p>
  </div>

  <h4>Order Items</h4>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Product ID</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Price (Rs)</th>
      </tr>
    </thead>
    <tbody>
      <?php while($item = $items_result->fetch_assoc()): ?>
      <tr>
        <td><?= $item['product_id']; ?></td>
        <td><?= htmlspecialchars($item['product_name']); ?></td>
        <td><?= $item['quantity']; ?></td>
        <td><?= number_format($item['price'], 2); ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <a href="orders.php" class="btn btn-back mt-3">Back to Orders</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
