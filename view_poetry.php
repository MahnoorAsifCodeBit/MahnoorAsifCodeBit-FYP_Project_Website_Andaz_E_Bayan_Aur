<?php
include 'config.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

// Fetch poetry records
$sql = "SELECT p.*, c.title AS category_name 
        FROM poetry_content p 
        LEFT JOIN categories c ON p.category_id = c.id 
        ORDER BY p.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View All Poetry - Admin Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: #f5f6fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Sidebar (same as previous) */
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

    /* Card/Table Styling */
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
      padding: 20px;
    }
    .card h3 {
      font-weight: 600;
      margin-bottom: 20px;
    }
    .table {
      border-radius: 10px;
      overflow: hidden;
    }
    .table thead {
      background: #800000;
      color: #fff;
    }
    .table tbody tr:hover {
      background-color: #f9f9f9;
      transition: 0.3s;
    }
    .table td, .table th {
      vertical-align: middle;
    }

    /* Search box */
    .search-box {
      margin-bottom: 20px;
    }
    .search-box input {
      border-radius: 10px;
      box-shadow: none;
    }

    /* Action buttons */
    .btn-action {
      border-radius: 8px;
      padding: 5px 12px;
      font-size: 0.9rem;
      font-weight: 500;
      text-decoration: none;
      color: #fff;
    }
    .btn-edit {
      background-color: #3498db;
    }
    .btn-edit:hover {
      background-color: #2980b9;
    }
    .btn-delete {
      background-color: #e74c3c;
    }
    .btn-delete:hover {
      background-color: #c0392b;
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
    <a href="view_poetry.php" class="active"><i class="fa fa-book me-2"></i>View Poetry</a>
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

    <!-- Poetry List -->
    <div class="card">
      <h3>View All Poetry</h3>

      <div class="search-box">
        <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search by Title, Author, or Category" onkeyup="searchTable()">
      </div>

      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Title</th>
              <th>Text</th>
              <th>Author</th>
              <th>Category</th>
              <th>Featured</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['title']); ?></td>
                  <td><?php echo nl2br(htmlspecialchars(substr($row['text'], 0, 80))) . '...'; ?></td>
                  <td><?php echo htmlspecialchars($row['author']); ?></td>
                  <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                  <td>
                    <?php if ($row['featured']): ?>
                      <span class="badge bg-success">Yes</span>
                    <?php else: ?>
                      <span class="badge bg-secondary">No</span>
                    <?php endif; ?>
                  </td>
                  <td><?php echo $row['created_at']; ?></td>
                  <td>
                    <a href="edit_poetry.php?id=<?php echo $row['content_id']; ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i> Edit</a>
                    <a href="delete_poetry.php?id=<?php echo $row['content_id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this poetry?');"><i class="fas fa-trash"></i> Delete</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-center">No poetry records found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function searchTable() {
  var input, filter, table, tr, td, i, j, txtValue;
  input = document.getElementById("searchInput");
  filter = input.value.toLowerCase();
  table = document.getElementById("poetryTable") || document.querySelector("table");
  tr = table.getElementsByTagName("tr");

  for (i = 1; i < tr.length; i++) {
    tr[i].style.display = "none";
    td = tr[i].getElementsByTagName("td");
    for (j = 0; j < td.length; j++) {
      if (td[j]) {
        txtValue = td[j].textContent || td[j].innerText;
        if (txtValue.toLowerCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
          break;
        }
      }
    }
  }
}
</script>
</body>
</html>
