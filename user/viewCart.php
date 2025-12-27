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

// Fetch cart items
if ($user_id) {
    $sql = "SELECT c.id as cart_id, p.id, p.name, p.image, p.price, c.quantity 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
} else {
    $sql = "SELECT c.id as cart_id, p.id, p.name, p.image, p.price, c.quantity 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.session_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $session_id);
}
$stmt->execute();
$result = $stmt->get_result();

$cart = [];
$grand_total = 0;
while ($row = $result->fetch_assoc()) {
    $cart[] = $row;
    $grand_total += $row['price'] * $row['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Cart | Andaz-e-Bayan Aur</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    body { background: #fdfdfb; font-family: 'Poppins', sans-serif; }
    .cart-table th { background: #4b0e19; color: #fff; }
    .btn-checkout { background: #4b0e19; color: #fff; border-radius: 8px; padding: 10px 20px; }
    .btn-checkout:hover { background: #721c24; }
    .btn-remove { background: #dc3545; color: #fff; border-radius: 5px; }
    .btn-remove:hover { background: #a71d2a; }


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

<div class="container py-5">
  <h2 class="mb-4">🛒 My Cart</h2>
  
  <?php if (empty($cart)) { ?>
    <div class="alert alert-warning">Your cart is empty.</div>
  <?php } else { ?>
    <form method="post" action="cartActions.php">
      <table class="table cart-table align-middle">
        <thead>
          <tr>
            <th>Product</th>
            <th>Image</th>
            <th>Price</th>
            <th width="120">Quantity</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart as $item) { 
            $total = $item['price'] * $item['quantity']; ?>
          <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><img src="../uploads/<?php echo $item['image']; ?>" width="70"></td>
            <td>Rs. <?php echo $item['price']; ?></td>
            <td>
              <input type="number" name="qty[<?php echo $item['cart_id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="form-control text-center">
            </td>
            <td>Rs. <?php echo $total; ?></td>
            <td>
              <a href="cartActions.php?remove=<?php echo $item['cart_id']; ?>" class="btn btn-remove btn-sm">Remove</a>
            </td>
          </tr>
          <?php } ?>
          <tr>
            <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
            <td><strong>Rs. <?php echo $grand_total; ?></strong></td>
            <td></td>
          </tr>
        </tbody>
      </table>

      <div class="d-flex justify-content-between">
        <a href="cartActions.php?clear=1" class="btn btn-danger">Clear Cart</a>
        <div>
          <button type="submit" name="update_qty" class="btn btn-primary">Update Cart</button>
          <a href="checkout.php" class="btn btn-checkout">Proceed to Checkout</a>
        </div>
      </div>
    </form>
  <?php } ?>
</div>

<script>
// update navbar badge on page load
fetch('getCartCount.php')
  .then(res => res.json())
  .then(data => {
    document.getElementById('cart-count').innerText = data.count;
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>
</html>
