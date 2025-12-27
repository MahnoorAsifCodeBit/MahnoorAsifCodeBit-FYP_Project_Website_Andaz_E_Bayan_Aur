<?php
session_start();
include("config.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

// Fetch all orders
$sql = "SELECT * FROM orders ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Orders | Andaz-e-Bayan Aur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    /* General Styles */
    body {
        background: #f3f1f0;
        font-family: 'Poppins', sans-serif;
    }
    .table-wrapper {
        margin: 100px auto;
        max-width: 1200px;
        padding: 30px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    h2 {
        color: #800000;
        font-weight: 600;
        margin-bottom: 30px;
    }

    /* Table Styles */
    table {
        border-radius: 8px;
        overflow: hidden;
    }
    thead {
        background-color: #800000;
        color: #fff;
    }
    thead th {
        font-weight: 500;
        border: none;
    }
    tbody tr {
        transition: all 0.3s ease;
    }
    tbody tr:hover {
        background-color: #f9e6e6;
    }
    tbody td {
        vertical-align: middle;
    }

    /* Form select styles */
    .status-select {
        min-width: 130px;
        border-radius: 5px;
        border: 1px solid #800000;
        color: #800000;
        font-weight: 500;
    }
    .status-select:focus {
        outline: none;
        box-shadow: 0 0 5px rgba(128,0,0,0.5);
    }

    /* Button Styles */
    .btn-primary {
        background-color: #800000;
        border-color: #800000;
    }
    .btn-primary:hover {
        background-color: #a30000;
        border-color: #a30000;
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
    <a href="orders.php"  class="active"><i class="fa fa-box me-2"></i>Orders</a>
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
<div class="container table-wrapper">
  <h2>Orders Management</h2>

  <table class="table table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Phone</th>
        <th>Total (Rs)</th>
        <th>Status</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if($result->num_rows > 0): ?>
        <?php while($order = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $order['id']; ?></td>
            <td><?= htmlspecialchars($order['customer_name']); ?></td>
            <td><?= htmlspecialchars($order['phone']); ?></td>
            <td><?= number_format($order['total_amount'], 2); ?></td>
            <td>
              <form method="post" action="update_order_status.php" class="d-flex">
                <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                <select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()">
                  <?php 
                  $statuses = ['Pending', 'Shipped', 'Delivered', 'Cancelled'];
                  foreach($statuses as $status): ?>
                    <option value="<?= $status; ?>" <?= $order['status'] == $status ? 'selected' : ''; ?>>
                      <?= $status; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </form>
            </td>
            <td><?= date('d M Y, H:i', strtotime($order['created_at'])); ?></td>
            <td>
              <a href="view_order.php?id=<?= $order['id']; ?>" class="btn btn-sm btn-primary">View</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="text-center">No orders found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
