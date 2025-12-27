<?php
include 'config.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

// Fetch categories for dropdown
$categoryQuery = "SELECT id, title FROM categories ORDER BY title ASC";
$categoryResult = $conn->query($categoryQuery);

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. Poetry ID is missing.");
}

$poetry_id = $_GET['id'];

// Fetch poetry details
$poetryQuery = "SELECT * FROM poetry_content WHERE content_id = $poetry_id";
$poetryResult = $conn->query($poetryQuery);

if ($poetryResult->num_rows != 1) {
    die("Poetry not found.");
}

$poetry = $poetryResult->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $text = $conn->real_escape_string($_POST['text']);
    $author = $conn->real_escape_string($_POST['author']);
    $category_id = $conn->real_escape_string($_POST['category_id']);
    $featured = isset($_POST['featured']) ? 1 : 0;

    $updateQuery = "UPDATE poetry_content SET 
        title = '$title',
        text = '$text',
        author = '$author',
        category_id = '$category_id',
        featured = '$featured'
        WHERE content_id = $poetry_id";

    if ($conn->query($updateQuery) === TRUE) {
        echo "<script>alert('Poetry updated successfully!'); window.location.href='view_poetry.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Poetry - Admin Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: #f5f6fa;
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

    /* Form card */
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
      padding: 25px;
      max-width: 700px;
      margin: auto;
    }
    .card h3 {
      font-weight: 600;
      margin-bottom: 20px;
      color: #800000;
    }

    label {
      font-weight: 500;
      margin-top: 10px;
      margin-bottom: 5px;
    }
    input[type="text"], textarea, select {
      border-radius: 10px !important;
    }

    /* Buttons */
    .btn-maroon {
      background-color: #800000;
      color: #fff;
      border-radius: 8px;
    }
    .btn-maroon:hover {
      background-color: #a00000;
    }
    .btn-outline-maroon {
      border: 1px solid #800000;
      color: #800000;
      border-radius: 8px;
    }
    .btn-outline-maroon:hover {
      background-color: #800000;
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
        <a href="view_poetry.php" class="btn btn-sm btn-outline-maroon me-2"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="dashboard.php" class="btn btn-sm btn-maroon me-2"><i class="fas fa-home"></i> Home</a>
        <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

    <!-- Edit Poetry Form -->
    <div class="card">
      <h3>Edit Poetry</h3>
      <form method="post">
        <div class="mb-3">
          <label>Title:</label>
          <input type="text" name="title" value="<?php echo htmlspecialchars($poetry['title']); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Text:</label>
          <textarea name="text" rows="8" class="form-control" required><?php echo htmlspecialchars($poetry['text']); ?></textarea>
        </div>

        <div class="mb-3">
          <label>Author:</label>
          <input type="text" name="author" value="<?php echo htmlspecialchars($poetry['author']); ?>" class="form-control" required>
        </div>

        <div class="mb-3">
          <label>Category:</label>
          <select name="category_id" class="form-select" required>
            <?php while ($cat = $categoryResult->fetch_assoc()): ?>
              <option value="<?php echo $cat['id']; ?>" <?php if($cat['id'] == $poetry['category_id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($cat['title']); ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" name="featured" id="featured" <?php echo $poetry['featured'] ? 'checked' : ''; ?>>
          <label class="form-check-label" for="featured">Mark as Featured</label>
        </div>

        <button type="submit" class="btn btn-maroon w-100"><i class="fas fa-save"></i> Update Poetry</button>
      </form>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
