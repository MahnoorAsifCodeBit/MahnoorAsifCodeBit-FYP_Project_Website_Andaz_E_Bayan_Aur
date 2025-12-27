<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

$success = false;
include 'config.php';

$id = $_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Prepare the update statement
    $update = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $update->bind_param("ssi", $name, $email, $id);

    if ($update->execute()) {
        $success = true;  // Success flag for modal
    } else {
        $error = $conn->error;  // Error message for alert
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User - Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      background: linear-gradient(135deg, #f8f9fa, #ececec);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
    .edit-card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      background: #fff;
    }
    .edit-card .card-left {
      background: linear-gradient(135deg, rgb(147,0,0), rgb(192,13,13));
      color: #fff;
      padding: 40px 30px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .edit-card .card-left h3 {
      font-weight: bold;
      margin-bottom: 20px;
    }
    .form-control:focus {
      border-color: rgb(147,0,0);
      box-shadow: 0 0 0 0.2rem rgba(147,0,0,.25);
    }
    .btn-custom {
      background: rgb(147,0,0);
      color: #fff;
      font-weight: 500;
      transition: 0.3s ease;
    }
    .btn-custom:hover {
      background: rgb(192,13,13);
      transform: translateY(-2px);
    }
    .input-group-text {
      background: #f8f9fa;
      border-right: 0;
    }
    .input-group .form-control {
      border-left: 0;
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
  <!-- Edit User Form -->
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card edit-card">
          <div class="row g-0">
            <!-- Left side (Info/Illustration) -->
            <div class="col-md-5 card-left">
              <h3><i class="fas fa-user-edit"></i> Edit User</h3>
              <p class="mb-0">Update user details and keep the records up to date.  
              Ensure that the information entered is correct before saving changes.</p>
              <div class="mt-4 text-center">
                <i class="fas fa-users fa-5x opacity-75"></i>
              </div>
            </div>

            <!-- Right side (Form) -->
            <div class="col-md-7 p-4">
              <form method="POST">
                <div class="mb-3">
                  <label class="form-label fw-semibold">Name</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Email</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                  </div>
                </div>
                <button type="submit" class="btn btn-custom w-100">
                  <i class="fas fa-save me-2"></i>Update User
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Success</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          User updated successfully!
        </div>
        <div class="modal-footer">
          <a href="user_list.php" class="btn btn-success">Go back to list</a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Stay here</button>
        </div>
      </div>
    </div>
  </div>

  <?php if ($success): ?>
  <script>
    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
  </script>
  <?php endif; ?>

  <?php if (isset($error)): ?>
  <script>alert("Error updating user: <?php echo $error; ?>");</script>
  <?php endif; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
