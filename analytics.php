<?php
include 'config.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

$total_poetry = $conn->query("SELECT COUNT(*) AS total FROM poetry_content")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
// Total Views/Clicks (from click_logs table)
$total_views = $conn->query("SELECT COUNT(*) AS total FROM click_logs")->fetch_assoc()['total'];

// Total Challenges
$total_challenges = $conn->query("SELECT COUNT(*) AS total FROM challenges")->fetch_assoc()['total'];

$top_categories = $conn->query("SELECT c.title, COUNT(p.category_id) AS count 
FROM categories c 
LEFT JOIN poetry_content p ON c.id = p.category_id 
GROUP BY c.id 
ORDER BY count DESC 
LIMIT 5");

$most_viewed_poetry = $conn->query("SELECT title, view_count FROM poetry_content ORDER BY view_count DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics Overview | Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background: #f4f6fc; }
    .page-title { font-weight: 600; margin-bottom: 25px; color: #2c3e50; }

    /* Cards */
    .card-custom {
      border: none;
      border-radius: 20px;
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(12px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
      transition: all 0.3s ease-in-out;
    }
    .card-custom:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

.metrics-bar {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 40px;
  padding: 30px 0;
  border-bottom: 1px solid #e5e7eb;
  margin-bottom: 30px;
}

.metric {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.metric-value {
  font-size: 2rem;
  font-weight: 700;
  color: #111827; /* dark slate */
  margin-bottom: 5px;
}

.metric-label {
  font-size: 0.95rem;
  font-weight: 500;
  color: #6b7280; /* gray */
  display: flex;
  align-items: center;
  gap: 6px;
}
.metric-label i {
  color: #9ca3af; /* lighter gray */
}

.bg-gradient-primary {
  background: linear-gradient(135deg, #6f42c1, #a770ef);
}
.bg-gradient-success {
  background: linear-gradient(135deg, #198754, #25d366);
}

.stat-text {
  display: flex;
  flex-direction: column;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  color: #222;
}

.stat-label {
  font-size: 0.9rem;
  color: #777;
  letter-spacing: 0.5px;
}


    /* Lists */
    .list-group-item {
      border: none;
      border-radius: 10px;
      margin-bottom: 8px;
      transition: all 0.2s;
    }
    .list-group-item:hover {
      background: #f0f3fa;
    }
    .poem-list li {
      background: #fdfdfd;
      border-radius: 10px;
      margin-bottom: 8px;
      padding: 12px 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      transition: all 0.2s ease;
    }
    .poem-list li:hover { background: #f0f3fa; }

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
    <a href="analytics.php" class="active"><i class="fa fa-chart-line me-2"></i>Analytics</a>
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
        <a href="dashboard.php" class="btn btn-sm btn-primary me-2"><i class="fas fa-home"></i> Home</a>
        <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

    <!-- Page Title -->
    <h2 class="page-title">Analytics Overview</h2>

<div class="metrics-bar">
  <div class="metric">
    <span class="metric-value"><?= $total_poetry; ?></span>
    <span class="metric-label"><i class="fas fa-book-open me-1"></i> Total Poetry</span>
  </div>
  <div class="metric">
    <span class="metric-value"><?= $total_users; ?></span>
    <span class="metric-label"><i class="fas fa-users me-1"></i> Total Users</span>
  </div>
  <div class="metric">
    <span class="metric-value"><?= $total_views; ?></span>
    <span class="metric-label"><i class="fas fa-eye me-1"></i> Total Views</span>
  </div>
  <div class="metric">
    <span class="metric-value"><?= $total_challenges; ?></span>
    <span class="metric-label"><i class="fas fa-flag me-1"></i> Challenges</span>
  </div>
</div>

    <!-- Categories + Poems Row -->
    <div class="row g-4">
      <!-- Top Categories -->
      <div class="col-lg-6">
        <div class="card card-custom p-3">
          <h5 class="mb-3"><i class="fas fa-layer-group text-warning"></i> Top 5 Categories</h5>
          <ul class="list-group">
            <?php while ($cat = $top_categories->fetch_assoc()) { ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= $cat['title']; ?>
                <span class="badge bg-primary rounded-pill"><?= $cat['count']; ?></span>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>

      <!-- Top Poems -->
      <div class="col-lg-6">
        <div class="card card-custom p-3">
          <h5 class="mb-3"><i class="fas fa-fire text-danger"></i> Top 5 Most Viewed Poems</h5>
          <ul class="poem-list list-unstyled mb-0">
            <?php $rank=1; while ($poem = $most_viewed_poetry->fetch_assoc()) { ?>
              <li>
                <span><strong>#<?= $rank; ?></strong> <?= $poem['title']; ?></span>
                <span><i class="fas fa-eye text-secondary"></i> <?= $poem['view_count']; ?></span>
              </li>
            <?php $rank++; } ?>
          </ul>
        </div>
      </div>
    </div>

    <!-- Traffic Chart -->
    <div class="row mt-4">
      <div class="col-12">
        <div class="card card-custom p-3">
          <h5 class="mb-3"><i class="fas fa-chart-line text-info"></i> Poetry Views Traffic</h5>
          <canvas id="trafficChart" height="120"></canvas>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
  fetch('chart_data.php')
    .then(response => response.json())
    .then(data => {
      const ctx = document.getElementById('trafficChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Views',
            data: data.dataPoints,
            borderColor: '#6f42c1',
            backgroundColor: 'rgba(111,66,193,0.1)',
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          plugins: { legend: { display: false } },
          scales: { 
            y: { beginAtZero: true, ticks: { color: '#7f8c8d' } },
            x: { ticks: { color: '#7f8c8d' } }
          }
        }
      });
    });
  </script>
</body>
</html>
