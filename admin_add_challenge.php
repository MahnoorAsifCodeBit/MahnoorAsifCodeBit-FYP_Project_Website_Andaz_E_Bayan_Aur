<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $scoring_method = $_POST['scoring_method'];

    // Determine status based on dates
    $current_time = date("Y-m-d H:i:s");
    if ($start_date > $current_time) {
        $status = 'upcoming';
    } elseif ($end_date < $current_time) {
        $status = 'expired';
    } else {
        $status = 'active';
    }

    // Insert challenge
    $query = "INSERT INTO challenges (title, description, category, start_date, end_date, scoring_method, status)
              VALUES ('$title', '$description', '$category', '$start_date', '$end_date', '$scoring_method', '$status')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Challenge added successfully!'); window.location='admin_challenges.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Add Challenge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
  
/* Page Header */
.page-header {
  margin: 20px 0 30px;
}
.page-header h2 {
  font-size: 1.75rem;
  font-weight: 600;
  color: #111827;
}
.page-header p {
  font-size: 0.95rem;
  color: #6b7280;
  margin: 5px 0 0;
}

/* Form Section */
.form-section {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 30px;
  max-width: 900px;
  margin: 0 auto;
}
.form-section .form-label {
  font-weight: 500;
  color: #374151;
  margin-bottom: 6px;
}
.form-control {
  border-radius: 8px;
  border: 1px solid #d1d5db;
  font-size: 15px;
  padding: 10px 14px;
  transition: border 0.2s, box-shadow 0.2s;
}
.form-control:focus {
  border-color: #6366f1;
  box-shadow: 0 0 0 2px rgba(99,102,241,0.2);
}

/* Submit Button */
.btn-submit {
  background: #700909ff;
  color: #fff;
  border: none;
  padding: 12px 22px;
  border-radius: 10px;
  font-weight: 600;
  transition: background 0.3s;
}
.btn-submit:hover {
  background: #a91212ff;
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

    /* Topbar */
    .topbar {
      background: #fff;
      padding: 15px 25px;
      border-radius: 12px;
      margin-bottom: 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      position: sticky;
      top: 0;
      z-index: 99;
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
    <a href="admin_add_challenge.php" class="active"><i class="fa fa-swords me-2"></i>Add Challenges</a>
    <a href="admin_challenges.php"><i class="fa fa-flag me-2"></i>View Challenges</a>
    <a href="orders.php"><i class="fa fa-box me-2"></i>Orders</a>
    <a href="admin_products.php"><i class="fa fa-shopping-cart me-2"></i>Calligraphy Products</a>
    <a href="change-password.php"><i class="fa fa-key me-2"></i>Change Password</a>
    <a href="logout.php"><i class="fa fa-sign-out me-2"></i>Logout</a>
  </div>

 <div class="main-content">

  <!-- Topbar -->
  <div class="topbar">
    <h5 class="mb-0">Welcome, <?= htmlspecialchars($admin_name); ?></h5>
    <div>
      <a href="dashboard.php" class="btn btn-sm btn-primary me-2"><i class="fas fa-home"></i> Home</a>
      <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </div>




  <!-- Page Header -->
  <div class="page-header">
    <h2>Add New Challenge</h2>
    <p class="text-muted">Fill in the details to create a new challenge.</p>
  </div>

  <!-- Form Section -->
  <div class="form-section">
    <form method="POST">
      <div class="row g-4">
        <div class="col-md-6">
          <label class="form-label">Title</label>
          <input type="text" class="form-control" name="title" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Category</label>
          <input type="text" class="form-control" name="category" required>
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea class="form-control" name="description" rows="4" required></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Start Date</label>
          <input type="datetime-local" class="form-control" name="start_date" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">End Date</label>
          <input type="datetime-local" class="form-control" name="end_date" required>
        </div>
        <label>Scoring Method:</label> <select name="scoring_method" required> 
          <option value="voting">Voting</option> 
          <option value="word_count">Word Count</option> 
          <option value="engagement">Engagement</option> 
          <option value="manual">manual</option> 
        </select> 
      </div>
      <div class="d-flex justify-content-end mt-4">
        <button type="submit" class="btn-submit"><i class="fas fa-plus me-2"></i> Add Challenge</button>
      </div>
    </form>
  </div>
</div>


</body>
</html>
