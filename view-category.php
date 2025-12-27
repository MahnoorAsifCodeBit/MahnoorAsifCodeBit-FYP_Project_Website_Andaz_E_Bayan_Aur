<?php
include 'config.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

// Fetch categories ordered by position
$result = $conn->query("SELECT * FROM categories ORDER BY position ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View & Reorder Categories - Admin</title>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f2f5;
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

    /* Content */
    .content {
      margin-left: 260px;
      padding: 25px;
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

    /* Table Styling */
    .table-wrapper {
      background: #fff;
      border-radius: 16px;
      padding: 25px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.08);
      animation: fadeIn 0.6s ease-in-out;
    }
    table {
      width: 100%;
    }
    thead {
      background: #800000;
      color: #fff;
    }
    th, td {
      vertical-align: middle !important;
      text-align: center;
    }
    tbody tr {
      background: #fafafa;
      transition: 0.3s;
      cursor: move;
    }
    tbody tr:hover {
      background: #f0e6e6;
    }
    .action-btns a {
      margin: 0 5px;
      text-decoration: none;
    }
    .btn-edit {
      color: #0d6efd;
    }
    .btn-delete {
      color: #dc3545;
    }
    .btn-save {
      margin-top: 15px;
      background: linear-gradient(45deg, #800000, #a30000);
      border: none;
      color: #fff;
      font-weight: 600;
      padding: 12px 20px;
      border-radius: 10px;
      transition: 0.3s;
    }
    .btn-save:hover {
      opacity: 0.9;
      transform: translateY(-2px);
    }

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
    <a href="add-category.php"><i class="fa fa-plus me-2"></i>Add Category</a>
    <a href="view-category.php" class="active"><i class="fa fa-list me-2"></i>View Categories</a>
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

    <!-- Table -->
    <div class="table-wrapper">
      <h4 class="mb-3">Manage & Reorder Categories</h4>
      <table class="table table-bordered table-hover" id="sortable-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Image</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="sortable">
          <?php while($row = $result->fetch_assoc()): ?>
          <tr data-id="<?php echo $row['id']; ?>">
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td>
              <?php if(!empty($row['image'])): ?>
                <img src="uploads/<?php echo $row['image']; ?>" width="80" class="rounded">
              <?php else: ?>
                <span class="text-muted">No image</span>
              <?php endif; ?>
            </td>
            <td class="action-btns">
              <a href="edit-category.php?id=<?php echo $row['id']; ?>" class="btn-edit"><i class="fas fa-edit"></i></a>
              <a href="delete-category.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash-alt"></i></a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <button id="saveOrder" class="btn-save"> Save New Order</button>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  $(function() {
    $("#sortable").sortable();
    $("#sortable").disableSelection();

    $("#saveOrder").click(function() {
      var order = [];
      $("#sortable tr").each(function(index) {
        order.push({ id: $(this).data("id"), position: index + 1 });
      });

      $.ajax({
        url: 'save_category_order.php',
        method: 'POST',
        data: { order: order },
        success: function(response) {
          alert('✅ Order saved successfully!');
          location.reload();
        },
        error: function() {
          alert('❌ Error saving order.');
        }
      });
    });
  });
  </script>
</body>
</html>
