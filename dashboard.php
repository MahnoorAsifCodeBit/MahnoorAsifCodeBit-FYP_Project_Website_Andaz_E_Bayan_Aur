<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

$conn = new mysqli("localhost", "root", "", "andazebayan");

// Stats
$poetry_count   = $conn->query("SELECT COUNT(*) AS total FROM poetry_content")->fetch_assoc()['total'];
$category_count = $conn->query("SELECT COUNT(*) AS total FROM categories")->fetch_assoc()['total'];
$user_count     = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$featured_count = $conn->query("SELECT COUNT(*) AS total FROM poetry_content WHERE featured = 1")->fetch_assoc()['total'];

// Poetry submissions (last 7 days)
$poetry_data = $conn->query("
  SELECT DAYNAME(created_at) AS day, COUNT(*) AS total
  FROM poetry_content
  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
  GROUP BY DAYOFWEEK(created_at)
  ORDER BY DAYOFWEEK(created_at)
")->fetch_all(MYSQLI_ASSOC);

// User registrations (last 6 months)
$user_data = $conn->query("
  SELECT DATE_FORMAT(created_at, '%b') AS month, COUNT(*) AS total
  FROM users
  WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
  GROUP BY YEAR(created_at), MONTH(created_at)
  ORDER BY YEAR(created_at), MONTH(created_at)
")->fetch_all(MYSQLI_ASSOC);

// Prepare data for JS
$poetry_labels = json_encode(array_column($poetry_data, 'day'));
$poetry_values = json_encode(array_column($poetry_data, 'total'));

$user_labels   = json_encode(array_column($user_data, 'month'));
$user_values   = json_encode(array_column($user_data, 'total'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f6f8fc;
      color: #333;
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
    /* Main */
    .main {
      margin-left: 260px;
      padding: 30px;
    }
    .welcome {
      font-size: 1.8rem;
      font-weight: 600;
      margin-bottom: 10px;
    }
    .subtitle {
      color: #666;
      margin-bottom: 30px;
    }
    /* Stats Cards */
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 25px;
      margin-bottom: 40px;
    }
    .stat-card {
      background: rgba(255,255,255,0.9);
      backdrop-filter: blur(10px);
      border-radius: 18px;
      padding: 25px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
      display: flex;
      align-items: center;
      transition: transform 0.3s ease;
    }
    .stat-card:hover { transform: translateY(-5px); }
    .stat-card i {
      font-size: 2.5rem;
      color: #a30000;
      margin-right: 20px;
    }
    .stat-card h4 {
      margin: 0;
      font-size: 1.1rem;
      font-weight: 600;
    }
    .stat-card h2 {
      margin: 5px 0 0;
      font-size: 1.5rem;
      font-weight: 700;
      color: #333;
    }
    /* Quick Actions */
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      margin-bottom: 40px;
    }
    .cards a {
      background: #fff;
      border-radius: 14px;
      padding: 20px;
      text-decoration: none;
      font-weight: 600;
      color: #333;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
      transition: all 0.3s;
    }
    .cards a i {
    margin-right: 8px; /* Adjust space as needed */
    color: #333;       /* Optional: icon color */
}

    .cards a:hover {
      background: linear-gradient(135deg, #a30000, #d94141);
      color: #fff;
      transform: translateY(-5px);
    }
    /* Charts */
    h3 { margin-top: 40px; font-size: 1.3rem; font-weight: 600; }
    .chart-container {
      background: #fff;
      border-radius: 16px;
      padding: 25px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
      margin-bottom: 40px;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php" class="active"><i class="fa fa-home me-2"></i>Dashboard</a>
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
  <div class="main">
    <div class="welcome">Welcome, <?php echo $admin_name; ?> </div>
    <p class="subtitle">Here’s a quick overview of your platform</p>

    <!-- Stats -->
    <div class="stats-container">
      <div class="stat-card"><i class="fa fa-book-open"></i><div><h4>Total Poetry</h4><h2><?php echo $poetry_count; ?></h2></div></div>
      <div class="stat-card"><i class="fa fa-folder-open"></i><div><h4>Categories</h4><h2><?php echo $category_count; ?></h2></div></div>
      <div class="stat-card"><i class="fa fa-users"></i><div><h4>Users</h4><h2><?php echo $user_count; ?></h2></div></div>
      <div class="stat-card"><i class="fa fa-star"></i><div><h4>Featured Poetry</h4><h2><?php echo $featured_count; ?></h2></div></div>
    </div>

    <!-- Quick Actions -->
    <div class="cards">
  <a href="add-category.php"><i class="fas fa-plus"></i>  Add Category</a>
  <a href="view-category.php"><i class="fas fa-search"></i> View Categories</a>
  <a href="add_poetry.php"><i class="fas fa-pen-nib"></i> Add Poetry</a>
  <a href="view_poetry.php"><i class="fas fa-book"></i> View Poetry</a>
  <a href="user_list.php"><i class="fas fa-users"></i> Manage Users</a>
  <a href="analytics.php"><i class="fas fa-chart-line"></i> Analytics</a>
  <a href="admin_add_challenge.php"><i class="fas fa-plus-circle"></i> Add Challenges</a>
  <a href="admin_challenges.php"><i class="fas fa-crosshairs"></i> View Challenges</a>
  <a href="orders.php"><i class="fas fa-box"></i> Orders</a>
  <a href="admin_products.php"><i class="fas fa-shopping-cart"></i> Calligraphy Products</a>
</div>


    <!-- Charts -->
    <div class="chart-container">
      <h3>Poetry Submissions</h3>
      <canvas id="poetryChart"></canvas>
    </div>
    <div class="chart-container">
      <h3>User Registrations</h3>
      <canvas id="userChart"></canvas>
    </div>
  </div>

<script>
const poetryChart = new Chart(document.getElementById("poetryChart"), {
  type: "bar",
  data: {
    labels: <?php echo $poetry_labels; ?>,
    datasets: [{
      label: "Poetry Submissions",
      data: <?php echo $poetry_values; ?>,
      backgroundColor: "rgba(163,0,0,0.7)",
      borderRadius: 6
    }]
  },
  options: { responsive: true, plugins: { legend: { display: false } } }
});

const userChart = new Chart(document.getElementById("userChart"), {
  type: "line",
  data: {
    labels: <?php echo $user_labels; ?>,
    datasets: [{
      label: "User Registrations",
      data: <?php echo $user_values; ?>,
      borderColor: "rgba(209,65,65,0.9)",
      tension: 0.4,
      fill: false,
      pointBackgroundColor: "#a30000",
      pointBorderWidth: 2
    }]
  },
  options: { responsive: true, plugins: { legend: { display: false } } }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
