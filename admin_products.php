<?php
session_start();
include("config.php");

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $image = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $name, $description, $price, $stock, $image);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_products.php");
    exit;
}

// Handle Delete Product
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_products.php");
    exit;
}

// Handle Sort Order Update (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    if (!isset($_POST['order']) || !is_array($_POST['order'])) exit('No order received');

    $order = $_POST['order'];
    foreach ($order as $position => $id) {
        $stmt = $conn->prepare("UPDATE products SET sort_order = ? WHERE id = ?");
        $stmt->bind_param("ii", $position, $id);
        $stmt->execute();
        $stmt->close();
    }
    echo "success";
    exit;
}

// Fetch products
$products = $conn->query("SELECT * FROM products ORDER BY sort_order ASC, created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Products | Andaz-e-Bayan Aur</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f3f1f0;
    margin: 0;
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

/* Table styles */
.table-wrapper {
    padding: 25px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
h2 {  margin-bottom: 20px; }
.product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }
.drag-handle { cursor: move; font-size: 18px; text-align: center; }
.table thead { background-color: #800000; color: #fff; }
.table tbody tr:hover { background-color: #f9e6e6; }

/* Buttons side by side */
.action-buttons { display: flex; gap: 8px; justify-content: center; }
.btn-primary, .btn-success, .btn-danger { font-weight: 500; }
.btn-primary { background-color: #800000; border-color: #800000; }
.btn-primary:hover { background-color: #a30000; border-color: #a30000; }
.btn-success { background-color: #800000; border-color: #800000; }
.btn-success:hover { background-color: #a30000; border-color: #a30000; }
.btn-danger { background-color: #b00000; border-color: #b00000; }
.btn-danger:hover { background-color: #d00000; border-color: #d00000; }

/* Modal */
.modal-header { background-color: #800000; color: #fff; }
.modal-title { font-weight: 600; }

/* Toast */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 10px 15px;
    background: #28a745;
    color: white;
    border-radius: 5px;
    z-index: 9999;
}

@media (max-width: 768px) {
    .sidebar { width: 100%; height: auto; position: relative; top: 0; }
    .main-content { margin-left: 0; padding-top: 140px; }
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
    <a href="admin_products.php" class="active"><i class="fa fa-shopping-cart me-2"></i>Calligraphy Products</a>
    <a href="change-password.php"><i class="fa fa-key me-2"></i>Change Password</a>
    <a href="logout.php"><i class="fa fa-sign-out me-2"></i>Logout</a>
</div>

<!-- Main content -->
<div class="main-content">
      <!-- Topbar -->
    <div class="topbar">
      <h5 class="mb-0">Welcome, <?= htmlspecialchars($admin_name); ?></h5>
      <div>
        <a href="dashboard.php" class="btn btn-sm btn-primary me-2"><i class="fas fa-home"></i> Home</a>
        <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>
    <div class="table-wrapper">
        <h2>Products Management</h2>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fa fa-plus me-1"></i>Add Product
        </button>

        <table class="table table-bordered table-hover bg-white shadow-sm" id="productsTable">
            <thead>
                <tr>
                    <th style="width: 30px;">☰</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="sortable">
                <?php if($products->num_rows > 0): ?>
                    <?php while($p = $products->fetch_assoc()): ?>
                    <tr data-id="<?= $p['id']; ?>">
                        <td class="drag-handle">☰</td>
                        <td>
                            <?php if($p['image']): ?>
                                <img src="uploads/<?= $p['image']; ?>" class="product-img">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['name']); ?></td>
                        <td><?= htmlspecialchars($p['description']); ?></td>
                        <td>Rs <?= number_format($p['price'],2); ?></td>
                        <td><?= $p['stock']; ?></td>
                        <td><?= date('d M Y', strtotime($p['created_at'])); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="admin_edit_product.php?id=<?= $p['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="?delete=<?= $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center">No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control"></textarea></div>
          <div class="mb-3"><label class="form-label">Price</label><input type="number" step="0.01" name="price" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Stock</label><input type="number" name="stock" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Image</label><input type="file" name="image" class="form-control"></div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button class="btn btn-success" type="submit">Add Product</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
new Sortable(document.getElementById('sortable'), {
    handle: '.drag-handle',
    animation: 150,
    onEnd: function () {
        let order = [];
        document.querySelectorAll('#sortable tr').forEach(row => { order.push(row.getAttribute('data-id')); });

        let formData = new FormData();
        formData.append('update_order', 1);
        order.forEach(id => formData.append('order[]', id));

        fetch('admin_products.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(res => {
            if(res.trim() === 'success') {
                let toast = document.createElement('div');
                toast.className = 'toast-notification';
                toast.textContent = 'Order saved!';
                document.body.appendChild(toast);
                setTimeout(()=>toast.remove(), 2000);
            } else { alert('Failed to update order: ' + res); }
        });
    }
});
</script>
</body>
</html>
