<?php
include 'config.php'; 
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $text = trim($_POST['text']);
    $author = trim($_POST['author']);
    $category_id = $_POST['category_id'];
    $featured = isset($_POST['featured']) ? 1 : 0;

    $sql = "INSERT INTO poetry_content (title, text, author, category_id, featured) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $title, $text, $author, $category_id, $featured);

    if ($stmt->execute()) {
        header("Location: ".$_SERVER['PHP_SELF']."?success=1");
        exit();
    } else {
        header("Location: ".$_SERVER['PHP_SELF']."?error=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Poetry - Admin Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
        background: #f5f6fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    /* Sidebar */
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
  margin-left: 260px; /* same as sidebar width */
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

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
    }
    .form-control, .form-select, .form-check-input {
        border-radius: 10px;
        box-shadow: none;
    }
    .btn-custom {
        background-color: #800000;
        color: #fff;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 500;
    }
    .btn-custom:hover {
        background-color: #a00000;
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
    

        <div class="container mt-4">
            <div class="card p-4">
                <h3 class="mb-4">Add New Poetry</h3>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">✅ Poetry added successfully!</div>
                <?php elseif (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">❌ Something went wrong. Please try again.</div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Poetry Text</label>
                        <textarea name="text" rows="6" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Author</label>
                        <input type="text" name="author" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">--Select Category--</option>
                            <?php
                            $categories = $conn->query("SELECT * FROM categories");
                            while ($row = $categories->fetch_assoc()) {
                                echo "<option value='{$row['id']}'>{$row['title']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="featured" id="featured">
                        <label class="form-check-label" for="featured">
                            Mark as Featured
                        </label>
                    </div>

                    <button type="submit" class="btn btn-custom">Add Poetry</button>
                </form>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
