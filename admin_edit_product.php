<?php
session_start();
include("config.php");



if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
$admin_name = $_SESSION['admin_name'];



// Get product ID
$product_id = $_GET['id'] ?? 0;

// Fetch existing product data
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    die("Product not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Handle image upload
    $image = $product['image']; // keep old image by default
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $image = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image);
    }

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock=?, image=? WHERE id=?");
    $stmt->bind_param("ssdssi", $name, $description, $price, $stock, $image, $product_id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Product | Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body { background: #f8f9fa; font-family: 'Poppins', sans-serif; }
    .form-wrapper { max-width: 600px; margin: 80px auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); }
    .product-img { width: 100px; height: 100px; object-fit: cover; border-radius: 6px; margin-bottom: 10px; }
    .button{background-color: #800000; color: white; border-radius: 6px;}
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
    <a href="admin_add_challenge.php"><i class="fa fa-swords me-2"></i>Add Challenges</a>
    <a href="admin_challenges.php"><i class="fa fa-flag me-2"></i>View Challenges</a>
    <a href="orders.php"><i class="fa fa-box me-2"></i>Orders</a>
    <a href="admin_products.php"><i class="fa fa-shopping-cart me-2"></i>Calligraphy Products</a>
    <a href="change-password.php"><i class="fa fa-key me-2"></i>Change Password</a>
    <a href="logout.php"><i class="fa fa-sign-out me-2"></i>Logout</a>
</div>

<!-- Main content -->
<div class="main-content">
      <!-- Topbar -->
    <div class="topbar">
      <h5 class="mb-0">Welcome, <?= htmlspecialchars($admin_name); ?></h5>
      <div>
        <a href="admin_products.php" class="btn btn-sm  me-2"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="dashboard.php" class="btn btn-sm btn-primary me-2"><i class="fas fa-home"></i> Home</a>
        <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>


<div class="container form-wrapper">
    <h2 class="mb-4">Edit Product</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="<?= $product['stock']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <?php if($product['image']): ?>
                <img src="uploads/<?= $product['image']; ?>" class="product-img">
            <?php else: ?>
                <p>No image uploaded</p>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Change Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button class="button" type="submit">Update Product</button>
        <a href="admin_products.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
