<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];
// Get challenge ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid Challenge ID'); window.location='admin_challenges.php';</script>";
    exit();
}

$challenge_id = $_GET['id'];

// Fetch challenge details
$query = "SELECT * FROM challenges WHERE id = $challenge_id";
$result = mysqli_query($conn, $query);
$challenge = mysqli_fetch_assoc($result);

if (!$challenge) {
    echo "<script>alert('Challenge not found'); window.location='admin_challenges.php';</script>";
    exit();
}

// Update logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $scoring_method = $_POST['scoring_method'];

    // Determine status automatically
    $current_time = date("Y-m-d H:i:s");
    if ($start_date > $current_time) {
        $status = 'upcoming';
    } elseif ($end_date < $current_time) {
        $status = 'expired';
    } else {
        $status = 'active';
    }

    // Update challenge
    $update_query = "UPDATE challenges SET 
                     title = '$title', 
                     description = '$description', 
                     category = '$category', 
                     start_date = '$start_date', 
                     end_date = '$end_date', 
                     scoring_method = '$scoring_method', 
                     status = '$status'
                     WHERE id = $challenge_id";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Challenge updated successfully!'); window.location='admin_challenges.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Challenge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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


        /* Light Theme */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            background: white;
            padding: 20px;
            margin: 50px auto;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            /* color: maroon; */
            font-weight: 600;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        label {
            font-weight: 500;
            text-align: left;
        }

        input, textarea, select {
            width: 97%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: 0.3s;
        }

        input:focus, textarea:focus, select:focus {
            border-color: maroon;
            outline: none;
            box-shadow: 0 0 5px rgba(121, 0, 0, 0.56);
        }

        button {
            background: maroon;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            transition: 0.3s;
        }

        button:hover {
            background:rgb(163, 15, 15);
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
            }
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
      <h5 class="mb-0">Welcome, <?= htmlspecialchars($admin_name); ?></h5>
      <div>
        <a href="admin_challenges.php" class="btn btn-sm btn-outline-maroon me-2"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="dashboard.php" class="btn btn-sm btn-primary me-2"><i class="fas fa-home"></i> Home</a>
        <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<div class="container">
<!-- Modern Form Section -->
  <div class="form-wrapper">
    <h2 class="page-title"><i class="fas fa-edit"></i> Edit Challenge</h2>

    <form method="POST" class="edit-form">
      <div class="form-group">
        <label><i class="fas fa-heading"></i> Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($challenge['title']); ?>" required>
      </div>

      <div class="form-group">
        <label><i class="fas fa-align-left"></i> Description</label>
        <textarea name="description" required><?= htmlspecialchars($challenge['description']); ?></textarea>
      </div>

      <div class="form-group">
        <label><i class="fas fa-tags"></i> Category</label>
        <input type="text" name="category" value="<?= htmlspecialchars($challenge['category']); ?>" required>
      </div>

      <div class="form-group">
        <label><i class="fas fa-calendar-alt"></i> Start Date</label>
        <input type="datetime-local" name="start_date" value="<?= date('Y-m-d\TH:i', strtotime($challenge['start_date'])); ?>" required>
      </div>

      <div class="form-group">
        <label><i class="fas fa-calendar-check"></i> End Date</label>
        <input type="datetime-local" name="end_date" value="<?= date('Y-m-d\TH:i', strtotime($challenge['end_date'])); ?>" required>
      </div>

      <div class="form-group">
        <label><i class="fas fa-balance-scale"></i> Scoring Method</label>
        <select name="scoring_method" required>
          <option value="manual" <?= ($challenge['scoring_method'] == 'manual') ? 'selected' : ''; ?>>Manual</option>
        </select>
      </div>

      <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Update Challenge</button>
    </form>
  </div>
</div>


</body>
</html>


