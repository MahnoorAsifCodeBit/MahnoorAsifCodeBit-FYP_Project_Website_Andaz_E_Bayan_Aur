<?php
include 'config.php';
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

// Fetch all challenges
$query = "SELECT * FROM challenges ORDER BY start_date DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Manage Challenges</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: #f9fafb;
      font-family: 'Segoe UI', sans-serif;
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

    /* Page header */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .page-header h2 {
      font-size: 1.5rem;
      font-weight: 600;
      color: #111827;
    }
    .btn-add {
      background: #6c1010ff;
      color: #fff;
      padding: 10px 18px;
      border-radius: 8px;
      font-size: 0.95rem;
      font-weight: 500;
      text-decoration: none;
      transition: background 0.3s;
    }
    .btn-add:hover {
      background: #9e1919ff;
      color: #fff;
    }

    /* Table */
    .table-container {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      padding: 20px;
    }
    table {
      width: 100%;
    }
    thead {
      background: #f3f4f6;
    }
    thead th {
      font-weight: 600;
      font-size: 0.9rem;
      color: #374151;
      padding: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    tbody td {
      padding: 12px;
      font-size: 0.95rem;
      color: #374151;
      vertical-align: middle;
    }
    tbody tr:hover {
      background: #f9fafb;
    }

    /* Status badges */
    .badge {
      font-size: 0.8rem;
      font-weight: 500;
      padding: 6px 10px;
      border-radius: 6px;
    }
    .badge-active { background: #d1fae5; color: #065f46; }
    .badge-upcoming { background: #e0f2fe; color: #075985; }
    .badge-expired { background: #fee2e2; color: #991b1b; }

    /* Actions */
    .table-actions a {
      margin-right: 10px;
      text-decoration: none;
      font-size: 0.9rem;
    }
    .table-actions .edit { color: #f59e0b; }
    .table-actions .delete { color: #dc2626; }
    .table-actions .participants { color: #2563eb; }
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
    <a href="admin_challenges.php" class="active"><i class="fa fa-flag me-2"></i>View Challenges</a>
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

    <!-- Page Header -->
    <div class="page-header">
      <h2>Manage Challenges</h2>
      <a href="admin_add_challenge.php" class="btn-add"><i class="fas fa-plus me-1"></i> Add Challenge</a>
    </div>

    <!-- Table -->
    <div class="table-container">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Scoring</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
<?php
date_default_timezone_set('Asia/Karachi');

while ($row = mysqli_fetch_assoc($result)) {
    $start_ts = !empty($row['start_date']) ? strtotime($row['start_date']) : null;
    $end_ts   = !empty($row['end_date'])   ? strtotime($row['end_date'])   : null;
    $now = time();

    if ($start_ts && $start_ts > $now) {
        $status = 'upcoming';
    } elseif ($end_ts && $end_ts < $now) {
        $status = 'expired';
    } else {
        $status = 'active';
    }

    $badgeClass = $status === 'active'
        ? 'badge-active'
        : ($status === 'upcoming' ? 'badge-upcoming' : 'badge-expired');

    $start_display = $start_ts ? date('d M Y H:i', $start_ts) : '-';
    $end_display   = $end_ts   ? date('d M Y H:i', $end_ts)   : '-';
?>
    <tr>
        <td><?= htmlspecialchars($row['title']); ?></td>
        <td><?= htmlspecialchars($row['category']); ?></td>
        <td><?= $start_display; ?></td>
        <td><?= $end_display; ?></td>
        <td><span class="badge <?= $badgeClass; ?>"><?= ucfirst($status); ?></span></td>
        <td><?= htmlspecialchars(ucfirst($row['scoring_method'])); ?></td>
        <td class="table-actions">
            <a href="admin_challenge_participants.php?id=<?= $row['id']; ?>" class="participants"><i class="fas fa-users"></i> Participants</a>
            <a href="admin_edit_challenge.php?id=<?= $row['id']; ?>" class="edit"><i class="fas fa-edit"></i> Edit</a>
            <a href="admin_delete_challenge.php?id=<?= $row['id']; ?>" onclick="return confirm('Are you sure?');" class="delete"><i class="fas fa-trash-alt"></i> Delete</a>
        </td>
    </tr>
<?php } ?>

        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
