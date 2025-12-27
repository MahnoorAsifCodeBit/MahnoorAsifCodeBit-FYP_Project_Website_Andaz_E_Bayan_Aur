<?php
session_start();
include("../config.php");


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../userlogin.php");
    exit();
}

// User info
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT name, profile_pic FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $user_name = $user['name'];

    if (!empty($user['profile_pic'])) {
        if (str_starts_with($user['profile_pic'], 'uploads/')) {
            $profile_pic = '/andazebayan-admin/user/' . $user['profile_pic']; 
        } else {
            $profile_pic = '/andazebayan-admin/user/images/' . $user['profile_pic'];
        }
    } else {
        $profile_pic = 'default.png';
    }
} else {
    $user_name = "Guest";
    $profile_pic = 'images/nodp.png';
}
$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();



// get user or guest session
$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();

// ✅ Pull cart from DB instead of $_SESSION
if ($user_id) {
    $sql = "SELECT c.product_id, c.quantity, p.name, p.price 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
} else {
    $sql = "SELECT c.product_id, c.quantity, p.name, p.price 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.session_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $session_id);
}
$stmt->execute();
$result = $stmt->get_result();
$cart = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle order placement
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($cart)) {
    $name    = trim($_POST['name']);
    $phone   = trim($_POST['phone']);
    $email   = trim($_POST['email']);
    $address = trim($_POST['address']);
    $payment = $_POST['payment'] ?? 'COD';

    if (!empty($name) && !empty($phone) && !empty($email) && !empty($address)) {
        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $total = $subtotal + 200;

        // ✅ Insert into orders table
        $sql = "INSERT INTO orders 
                (customer_name, phone, email, address, total_amount, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssd", $name, $phone, $email, $address, $total);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // ✅ Insert order items
        $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt_item = $conn->prepare($sql_item);
        foreach ($cart as $item) {
            $stmt_item->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
            $stmt_item->execute();
        }
        $stmt_item->close();

        // ✅ Clear cart from DB after checkout
        if ($user_id) {
            $clear = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $clear->bind_param("i", $user_id);
        } else {
            $clear = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
            $clear->bind_param("s", $session_id);
        }
        $clear->execute();
        $clear->close();

        // Redirect to success page
        header("Location: orderSuccess.php?order_id=" . $order_id);
        exit;
    } else {
        $error = "⚠️ Please fill in all required fields.";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout | Andaz-e-Bayan Aur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

<script type="text/javascript">
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,ur,ar,hi,fr,id,ja',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
}
</script>
  <style>
    body { background: #f8f9fa; font-family: 'Poppins', sans-serif; }

    .checkout-wrapper { max-width: 1100px; margin: auto; }
    .checkout-card {
      background: #fff;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }
    .checkout-title {
      font-family: 'Playfair Display', serif;
      color: #6c0d1e;
      font-weight: 600;
      margin-bottom: 20px;
      font-size: 1.6rem;
    }
    .btn-maroon {
      background-color: #6c0d1e;
      color: #fff;
      border-radius: 10px;
      font-weight: 500;
      padding: 12px;
    }
    .btn-maroon:hover { background-color: #500a15; }
    .form-check-label { font-weight: 500; }

    /* Progress Steps */
    .progressbar {
      display: flex;
      justify-content: space-between;
      margin-bottom: 40px;
      counter-reset: step;
    }
    .progressbar li {
      list-style-type: none;
      width: 100%;
      text-align: center;
      position: relative;
      color: #999;
      font-size: 0.9rem;
    }
    .progressbar li:before {
      content: counter(step);
      counter-increment: step;
      width: 35px; height: 35px;
      line-height: 35px;
      border: 2px solid #6c0d1e;
      display: block;
      text-align: center;
      margin: 0 auto 10px;
      border-radius: 50%;
      background: #fff;
      color: #6c0d1e;
      font-weight: bold;
    }
    .progressbar li.active { color: #6c0d1e; font-weight: 600; }
    .progressbar li.active:before { background: #6c0d1e; color: #fff; }
    .progressbar li:after {
      content: '';
      position: absolute;
      width: 100%;
      height: 2px;
      background: #ccc;
      top: 16px;
      left: -50%;
      z-index: -1;
    }
    .progressbar li:first-child:after { content: none; }
    .progressbar li.active + li:after { background: #6c0d1e; }


.navbar { position: relative; width: 99vw; height: 70px; background: #4b0e19; padding: 10px 20px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); display: flex; align-items: center; justify-content: space-between; }
.navbar-brand img { max-height: 80px; margin-right: 10px; }
.navbar-nav { gap: 40px; }
.navbar-nav .nav-link { color: white !important; font-weight: 600; }
.navbar-nav .nav-link:hover { color: #FFDAB9 !important; text-shadow: 0 0 6px rgba(255,255,255,0.5); border-bottom: 2px solid white; }
.nav-image { height: 50px; width: 50px; border-radius: 50%; object-fit: cover; margin-left: 8px; border: 1px solid rgb(108,0,0); }
.cart-icon { position: relative; }
.cart-badge { position: absolute; top: -8px; right: -12px; background: #FFD700; color: #000; font-size: 12px; border-radius: 50%; padding: 3px 7px; font-weight: bold; }



.goog-te-gadget img { display: none; }
.goog-te-gadget-simple { background-color: #4b0e19 !important; border: none !important; color: white !important; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
.goog-te-gadget-simple:hover { background-color: #4b0e19 !important; }
.goog-te-gadget-simple .VIpgJd-ZVi9od-xl07Ob-lTBxed { color: rgb(255, 255, 255) !important; font-size: 16px; font-weight: bold; }
  </style>
</head>
<body>
  <!-- Navbar -->
<nav class="navbar navbar-expand-lg">
<div class="container-fluid">
  <a class="navbar-brand d-flex align-items-center" href="index.php">
    <img src="images/white.png" alt="Logo">
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav me-auto">
      <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="userCategory.php">Category</a></li>
      <li class="nav-item"><a class="nav-link" href="customization.php">Customization</a></li>
      <li class="nav-item"><a class="nav-link" href="challenges.php">Challenges</a></li>
      <li class="nav-item"><a class="nav-link" href="e-commerce.php">Merchandise</a></li>
      <li class="nav-item"><a class="nav-link" href="team.php">Team</a></li>
      <li class="nav-item dropdown"><div id="google_translate_element"></div></li>
    </ul>
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
          <img src="<?php echo $profile_pic; ?>" class="nav-image"> &nbsp; <?php echo htmlspecialchars($user_name);?>
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="viewProfile.php">Profile</a></li>
          <li><a class="dropdown-item" href="../userLogout.php">Logout</a></li>
        </ul>
      </li>
    </ul>
  </div>
</div>
</nav>
<div class="container checkout-wrapper py-5">
  
  <!-- Progress bar -->
  <ul class="progressbar">
    <li class="active">Cart</li>
    <li class="active">Checkout</li>
    <li>Success</li>
  </ul>

  <div class="row g-4">
    <!-- Billing Form -->
    <div class="col-lg-7">
      <div class="checkout-card">
        <h3 class="checkout-title">📝 Billing Information</h3>
        <form method="post">
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Delivery Address</label>
            <textarea name="address" class="form-control" rows="3" required></textarea>
          </div>

          <h5 class="mt-4 mb-2">💳 Payment Method</h5>
          <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="payment" value="COD" checked>
            <label class="form-check-label">Cash on Delivery</label>
          </div>
          <!-- <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="payment" value="Card">
            <label class="form-check-label">Credit / Debit Card</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="radio" name="payment" value="Bank Transfer">
            <label class="form-check-label">Bank Transfer</label>
          </div> -->

          <button type="submit" class="btn btn-maroon w-100 mt-4">Place Order</button>
        </form>
      </div>
    </div>

<!-- Order Summary -->
<div class="col-md-5">
  <div class="checkout-card">
    <h3 class="checkout-title">📦 Order Summary</h3>
    <ul class="list-group mb-3">
      <?php 
      $subtotal = 0;
      if (!empty($cart) && is_array($cart)) {
          foreach ($cart as $item): 
              $subtotal += $item['price'] * $item['quantity'];
      ?>
          <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0"><?= htmlspecialchars($item['name']); ?></h6>
              <small class="text-muted">Qty: <?= $item['quantity']; ?></small>
            </div>
            <span>Rs <?= $item['price'] * $item['quantity']; ?></span>
          </li>
      <?php 
          endforeach;
      } else {
          echo '<li class="list-group-item">Your cart is empty.</li>';
      }
      ?>
      <li class="list-group-item d-flex justify-content-between">
        <span>Subtotal</span>
        <strong>Rs <?= $subtotal; ?></strong>
      </li>
      <li class="list-group-item d-flex justify-content-between">
        <span>Delivery</span>
        <strong>Rs 200</strong>
      </li>
      <li class="list-group-item d-flex justify-content-between">
        <span>Total</span>
        <strong>Rs <?= $subtotal + 200; ?></strong>
      </li>
    </ul>
  </div>
</div>

  </div>
</div>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</body>
</html>
