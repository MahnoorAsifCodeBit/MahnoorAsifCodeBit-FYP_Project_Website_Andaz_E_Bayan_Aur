<?php 
include 'config.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

// --- Form handling (restored) ---
$error = '';
if (isset($_POST['submit'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);

    if (!empty($_FILES['image']['name'])) {
        $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $imageName;

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO categories (title, description, image) VALUES ('$title', '$description', '$imageName')";
            if ($conn->query($sql) === TRUE) {
                echo "<script>
                        alert('✅ Category added successfully!');
                        window.location.href = 'view-category.php';
                      </script>";
                exit();
            } else {
                $error = "❌ Database Error: " . $conn->error;
            }
        } else {
            $error = "❌ Error uploading image. Please try again.";
        }
    } else {
        $error = "❗ Please select an image file before submitting.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Category - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #f7f8fa, #eef1f6);
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* Sidebar */
    .sidebar {
      width: 260px;
      background: linear-gradient(180deg, #1a1d2b, #2a2f45);
      color: #fff;
      position: fixed;
      top: 0; bottom: 0; left: 0;
      padding: 20px 0;
      display: flex;
      flex-direction: column;
      box-shadow: 4px 0 20px rgba(0,0,0,0.2);
    }
    .sidebar h2 {
      text-align: center;
      font-size: 1.6rem;
      margin-bottom: 30px;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .sidebar a {
      padding: 14px 25px;
      display: block;
      color: #c7c7c7;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .sidebar a:hover, .sidebar a.active {
      background: rgba(255,255,255,0.08);
      color: #fff;
    }

    /* Content */
    .content {
      margin-left: 260px;
      padding: 30px;
    }

    /* Topbar */
    .topbar {
      background: #fff;
      padding: 15px 25px;
      border-radius: 14px;
      margin-bottom: 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    }

    /* Form Styling */
    .form-wrapper {
      max-width: 750px;
      margin: auto;
      padding: 45px 40px;
      border-radius: 18px;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(15px);
      box-shadow: 0 12px 32px rgba(0,0,0,0.1);
      animation: fadeIn 0.7s ease-in-out;
    }
    .form-wrapper h2 {
      text-align: center;
      color: #800000;
      font-weight: 700;
      margin-bottom: 30px;
      font-size: 1.7rem;
    }
    .form-label {
      font-weight: 600;
      color: #444;
    }
    .form-control {
      border-radius: 12px;
      padding: 14px;
      border: 1px solid #ddd;
      transition: 0.3s;
    }
    .form-control:focus {
      border-color: #800000;
      box-shadow: 0 0 0 0.25rem rgba(128,0,0,0.25);
    }

    /* Button */
    .btn-maroon {
      background: linear-gradient(45deg, #800000, #a30000);
      color: #fff;
      font-weight: 600;
      padding: 12px;
      border-radius: 12px;
      border: none;
      transition: 0.3s;
      box-shadow: 0 6px 14px rgba(128,0,0,0.3);
    }
    .btn-maroon:hover {
      opacity: 0.95;
      transform: translateY(-2px);
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php"><i class="fa fa-home me-2"></i>Dashboard</a>
    <a href="add-category.php" class="active"><i class="fa fa-plus me-2"></i>Add Category</a>
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
  <div class="content">
    <!-- Topbar -->
    <div class="topbar">
      <h5 class="mb-0">Welcome, <?php echo htmlspecialchars($admin_name); ?></h5>
      <div>
        <a href="dashboard.php" class="btn btn-sm btn-maroon me-2"><i class="fas fa-home"></i> Home</a>
        <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

    <!-- Form -->
    <div class="form-wrapper">
      <h2><i class="fa fa-layer-group me-2"></i> Add New Category</h2>

      <!-- show server-side errors -->
      <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Category Title</label>
          <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Upload Image</label>
          <input type="file" name="image" class="form-control" id="image" required>
        </div>
        <button type="submit" name="submit" class="btn btn-maroon w-100"><i class="fa fa-plus-circle me-1"></i> Add Category</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
