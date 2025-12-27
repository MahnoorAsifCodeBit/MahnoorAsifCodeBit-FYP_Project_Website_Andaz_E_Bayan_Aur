<?php
session_start();
include('config.php'); 

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'];
$message = "";
$message_type = ""; // success or danger

if (isset($_POST['change_password'])) {
    $old_password = mysqli_real_escape_string($conn, $_POST['old_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    $query = "SELECT password FROM admins WHERE admin_id='$admin_id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $current_password = $row['password'];

    if ($old_password != $current_password) {
        $message = "Old password is incorrect!";
        $message_type = "danger";
    } elseif ($new_password != $confirm_password) {
        $message = "New passwords do not match!";
        $message_type = "danger";
    } else {
        $update_query = "UPDATE admins SET password='$new_password' WHERE admin_id='$admin_id'";
        if (mysqli_query($conn, $update_query)) {
            $message = "Password changed successfully!";
            $message_type = "success";
        } else {
            $message = "Error updating password.";
            $message_type = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Password | Andaz-e-Bayan Aur</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f3f1f0;
    margin: 0;
}
     /* Sidebar */
    .sidebar {
      width: 260px;
      background: linear-gradient(180deg, #1a1d2b, #23263a);
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

.topbar {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.topbar .welcome-text {
    font-weight: 600;
    font-size: 1.5rem;
}

.topbar-buttons .btn {
    border-radius: 8px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.topbar-buttons .btn i {
    font-size: 0.9rem;
}
.btn-maroon{
  background-color: maroon;
  color: white;
}

/* Center card */
.change-pass-card {
    max-width: 500px;
    margin: 100px auto;
    padding: 30px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
.change-pass-card h2 {
    color: #800000;
    font-weight: 600;
    margin-bottom: 25px;
    text-align: center;
}
.form-label { font-weight: 500; }
.form-control { border-radius: 8px; }
.btn-primary {
    background-color: #800000;
    border-color: #800000;
    font-weight: 500;
    border-radius: 8px;
    width: 100%;
}
.btn-primary:hover {
    background-color: #a30000;
    border-color: #a30000;
}

/* Toast messages */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    color: #fff;
    border-radius: 8px;
    font-weight: 500;
    z-index: 9999;
}
.toast-success { background-color: #28a745; }
.toast-danger { background-color: #dc3545; }
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
    <a href="change-password.php" class="active"><i class="fa fa-key me-2"></i>Change Password</a>
    <a href="logout.php"><i class="fa fa-sign-out me-2"></i>Logout</a>
</div>

<!-- Main content -->
<div class="main-content">
<!-- Topbar -->
<div class="topbar d-flex justify-content-between align-items-center p-3 mb-4">
    <div class="welcome-text">
        Welcome, <?= htmlspecialchars($admin_name); ?>
    </div>
    <div class="topbar-buttons d-flex gap-2">
        <a href="dashboard.php" class="btn btn-maroon btn-sm">
            <i class="fas fa-home me-1"></i> Home
        </a>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
        </a>
    </div>
</div>


<div class="change-pass-card">
    <h2>Change Password</h2>

    <?php if($message != ""): ?>
        <div class="toast-notification <?= $message_type=='success'?'toast-success':'toast-danger' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Old Password</label>
            <input type="password" name="old_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" name="change_password" class="btn btn-primary"><i class="fa fa-key me-2"></i>Change Password</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
