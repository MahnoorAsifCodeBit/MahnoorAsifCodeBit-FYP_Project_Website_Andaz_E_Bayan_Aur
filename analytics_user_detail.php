<?php
include 'config.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];
$user_id = $_GET['id'];
$user = $conn->query("SELECT name, email FROM users WHERE id=$user_id")->fetch_assoc();

$logins = $conn->query("SELECT COUNT(*) AS login_count FROM login_logs WHERE user_id=$user_id")->fetch_assoc()['login_count'];
$category_clicks = $conn->query("SELECT COUNT(*) AS clicks FROM click_logs WHERE user_id=$user_id AND click_type='category'")->fetch_assoc()['clicks'];
$poetry_clicks = $conn->query("SELECT COUNT(*) AS clicks FROM click_logs WHERE user_id=$user_id AND click_type='poetry'")->fetch_assoc()['clicks'];
$template_downloads = $conn->query("SELECT COUNT(*) AS clicks FROM click_logs WHERE user_id=$user_id AND click_type='template_download'")->fetch_assoc()['clicks'];
$general_clicks = $conn->query("SELECT COUNT(*) AS clicks FROM click_logs WHERE user_id=$user_id AND click_type='general_click'")->fetch_assoc()['clicks'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Analytics Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: #f5f7fb;
      font-family: 'Segoe UI', sans-serif;
    }

    /* Sidebar stays same */
    .sidebar {
      width: 260px;
      background: #1a1d2b;
      color: #fff;
      position: fixed;
      top: 0; bottom: 0; left: 0;
      padding: 20px 0;
      display: flex;
      flex-direction: column;
      box-shadow: 4px 0 20px rgba(0,0,0,0.15);
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

    /* Main content */
    .main-content {
      margin-left: 260px;
      padding: 20px;
    }

    /* Topbar */
    .topbar {
      background: #fff;
      padding: 15px 25px;
      border-radius: 0;
      margin-bottom: 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      position: sticky;
      top: 0;
      z-index: 99;
    }

    /* Analytics Card */
    .analytics-card {
      max-width: 850px;
      margin: 0 auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
      padding: 35px;
    }
    .analytics-card h2 {
      font-size: 26px;
      font-weight: 700;
      margin-bottom: 10px;
    }
    .analytics-card p {
      font-size: 15px;
      color: #6c757d;
    }

    /* Stats Row */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
      gap: 20px;
      margin-top: 30px;
    }
    .stat-box {
      background: #f9fafc;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      transition: 0.3s;
    }
    .stat-box:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .stat-box i {
      font-size: 28px;
      margin-bottom: 10px;
      display: block;
    }
    .stat-box h5 {
      margin: 0;
      font-size: 18px;
      font-weight: 600;
    }
    .stat-box span {
      font-size: 15px;
      color: #666;
    }

    /* Themed Buttons */
    .btn-maroon {
      background: maroon;
      color: #fff;
    }
    .btn-maroon:hover {
      background: #a00000;
      color: #fff;
    }
    .btn-outline-maroon {
      border: 1px solid maroon;
      color: maroon;
    }
    .btn-outline-maroon:hover {
      background: maroon;
      color: #fff;
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
        <a href="user_list.php" class="btn btn-sm btn-outline-maroon me-2"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="dashboard.php" class="btn btn-sm btn-maroon me-2"><i class="fas fa-home"></i> Home</a>
        <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

    <!-- Analytics Section -->
    <div class="analytics-card">
      <h2>Analytics for <strong><?php echo $user['name']; ?></strong></h2>
      <p><?php echo $user['email']; ?></p>

      <div class="stats-grid">
        <div class="stat-box">
          <i class="fas fa-sign-in-alt text-primary"></i>
          <h5><?php echo $logins; ?></h5>
          <span>Total Logins</span>
        </div>
        <div class="stat-box">
          <i class="fas fa-th-large text-success"></i>
          <h5><?php echo $category_clicks; ?></h5>
          <span>Category Clicks</span>
        </div>
        <div class="stat-box">
          <i class="fas fa-feather-alt text-info"></i>
          <h5><?php echo $poetry_clicks; ?></h5>
          <span>Poetry Clicks</span>
        </div>

      </div>

      <div class="mt-4 text-center">
        <a href="analytics.php" class="btn btn-maroon"><i class="fas fa-chart-line"></i> Analytics Overview</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
