<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Invalid Challenge ID'); window.location='admin_challenges.php';</script>";
    exit();
}

$challenge_id = (int) $_GET['id'];

$challenge_query = "SELECT title, scoring_method FROM challenges WHERE id = $challenge_id";
$challenge_result = mysqli_query($conn, $challenge_query);
$challenge = mysqli_fetch_assoc($challenge_result);

if (!$challenge) {
    echo "<script>alert('Challenge not found'); window.location='admin_challenges.php';</script>";
    exit();
}

$participants_query = "
    SELECT challenge_participants.id, users.name, challenge_participants.entry_text, challenge_participants.score
    FROM challenge_participants
    JOIN users ON challenge_participants.user_id = users.id
    WHERE challenge_participants.challenge_id = $challenge_id
    ORDER BY challenge_participants.score DESC";
$participants_result = mysqli_query($conn, $participants_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Participants - <?= htmlspecialchars($challenge['title']); ?></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f7fb;
      color: #333;
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

    /* Header */
    .page-header {
      margin: 30px auto 20px;
      text-align: center;
    }
    .page-header h2 {
      font-weight: 700;
      color: #222;
    }

    /* Card */
    .card-custom {
      border: none;
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      margin: auto;
      padding: 20px;
      background: #fff;
    }

    /* Table */
    .table thead th {
      background: #800000;
      color: #fff;
      text-align: center;
      vertical-align: middle;
      border: none;
    }
    .table tbody td {
      text-align: center;
      vertical-align: middle;
    }
    .table tbody tr:hover {
      background: #f9f2f2;
    }

    /* Score Badge */
    .score-badge {
      /* background: #ff6a00; */
      color: black;
      padding: 6px 12px;
      border-radius: 50px;
      font-size: 13px;
      font-weight: 600;
    }

    /* Buttons */
    .action-btn {
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 13px;
      margin: 2px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .edit-btn {
      background: #198754;
      color: #fff;
    }
    .edit-btn:hover {
      background: #157347;
    }
    .delete-btn {
      background: #dc3545;
      color: #fff;
    }
    .delete-btn:hover {
      background: #bb2d3b;
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


<!-- Header -->
<div class="page-header">
  <h2><i class="fas fa-users text-danger me-2"></i>Participants - <?= htmlspecialchars($challenge['title']); ?></h2>
</div>

<!-- Participants Table -->
<div class="card card-custom">
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead>
        <tr>
          <th>User</th>
          <th>Poetry Entry</th>
          <th>Score</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($participants_result)): ?>
          <tr>
            <td><i class="fas fa-user-circle me-1 text-muted"></i><?= htmlspecialchars($row['name']); ?></td>
            <td class="text-start"><?= nl2br(htmlspecialchars($row['entry_text'])); ?></td>
            <td><span class="score-badge"><?= $row['score']; ?></span></td>
            <td>
              <?php if ($challenge['scoring_method'] == 'manual'): ?>
                <a href="admin_update_scores.php?participant_id=<?= $row['id']; ?>" class="action-btn edit-btn">
                  <i class="fas fa-edit"></i> Update
                </a>
              <?php endif; ?>
              <a href="admin_remove_participant.php?participant_id=<?= $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to disqualify this participant?');">
                <i class="fas fa-trash"></i> Disqualify
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
