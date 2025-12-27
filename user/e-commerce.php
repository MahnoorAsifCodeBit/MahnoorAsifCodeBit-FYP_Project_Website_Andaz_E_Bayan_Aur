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

// ----- Search/Pagination -----
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$perPage = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

// Build query
$sql = "SELECT * FROM products WHERE 1";
if ($search) $sql .= " AND name LIKE '%$search%'";

// Count total products
$sql_count = str_replace("*", "COUNT(*) as total", $sql);
$total_res = mysqli_query($conn, $sql_count);
$total_row = mysqli_fetch_assoc($total_res);
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $perPage);

// Add ordering and limit (use admin-defined sort_order first)
$sql .= " ORDER BY sort_order ASC, created_at DESC LIMIT $offset, $perPage";
$products_res = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shop | Andaz-e-Bayan Aur</title>
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
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<style>
body { background: #fdfdfb; font-family: 'Poppins', sans-serif; color: #444; }
.navbar { position: relative; width: 99vw; height: 70px; background: #4b0e19; padding: 10px 20px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); display: flex; align-items: center; justify-content: space-between; }
.navbar-brand img { max-height: 80px; margin-right: 10px; }
.navbar-nav { gap: 40px; }
.navbar-nav .nav-link { color: white !important; font-weight: 600; }
.navbar-nav .nav-link:hover { color: #FFDAB9 !important; text-shadow: 0 0 6px rgba(255,255,255,0.5); border-bottom: 2px solid white; }
.nav-image { height: 50px; width: 50px; border-radius: 50%; object-fit: cover; margin-left: 8px; border: 1px solid rgb(108,0,0); }
.cart-icon { position: relative; }
.cart-badge { position: absolute; top: -8px; right: -12px; background: #FFD700; color: #000; font-size: 12px; border-radius: 50%; padding: 3px 7px; font-weight: bold; }

.hero { background: #4b0e19; color: #fff; text-align: center; padding: 50px 20px; }
.hero h1 { font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 600; }
.section-title { text-align: center; font-size: 1.9rem; font-family: 'Playfair Display', serif; font-weight: 600; margin-bottom: 30px; color: #4b0e19; }

.search-bar-container { max-width: 600px; margin: -30px auto 40px; position: relative; }
.search-bar-container input { width: 100%; padding: 14px 50px 14px 20px; border-radius: 30px; border: 1px solid #ccc; font-size: 1rem; transition: all 0.3s; }
.search-bar-container input:focus { border-color: #4b0e19; outline: none; box-shadow: 0 0 8px rgba(75,14,25,0.3); }
.search-bar-container button { position: absolute; right: 5px; top: 50%; transform: translateY(-50%); background: #4b0e19; color: #fff; border: none; padding: 10px 20px; border-radius: 25px; cursor: pointer; transition: 0.3s; }
.search-bar-container button:hover { background: #721c24; }

.product-card { border: none; border-radius: 14px; background: #fff; transition: 0.3s; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.product-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,0.1); transform: translateY(-4px); }
.product-card img { height: 240px; object-fit: cover; width: 100%; }
.product-card h5 { font-size: 1.05rem; font-weight: 600; margin-bottom: 8px; }
.price { font-size: 1rem; font-weight: 500; color: #4b0e19; margin-bottom: 12px; }
.btn-buy { border-radius: 8px; background: #4b0e19; color: #fff; padding: 9px 16px; width: 100%; }
.btn-buy:hover { background: #721c24; }

.goog-te-gadget img { display: none; }
.goog-te-gadget-simple { background-color: #4b0e19 !important; border: none !important; color: white !important; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
.goog-te-gadget-simple:hover { background-color: #4b0e19 !important; }
.goog-te-gadget-simple .VIpgJd-ZVi9od-xl07Ob-lTBxed { color: rgb(255, 255, 255) !important; font-size: 16px; font-weight: bold; }
    /* Footer */
.site-footer {
  background: #4b0e19;
  color: #fff;
  padding: 20px 10px;
  text-align: center;
  font-weight: bold;
}
.footer-links a {
  color: #fff;
  margin: 0 5px;
  font-size: 0.9em;
  font-weight: bold;
}
.footer-links a:hover {
  text-decoration: underline;
}
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
      <li class="nav-item">
        <a class="nav-link cart-icon" href="viewCart.php">
          🛒 Cart <span id="cart-count" class="cart-badge">0</span>
        </a>
      </li>
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

<!-- Hero -->
<section class="hero">
<h1>Shop</h1>
<p>Curated Calligraphy & Poetry Merchandise</p>
</section>

<!-- Search Section -->
<div class="search-bar-container">
  <form method="GET" class="d-flex">
    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
  </form>
</div>

<!-- Shop Grid -->
<div class="container py-3">
<h2 class="section-title">Products</h2>
<div class="row g-4">
<?php while($row = mysqli_fetch_assoc($products_res)) { ?>
  <div class="col-md-4 col-lg-3">
    <div class="card product-card h-100 text-center">
      <img src="../uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
      <div class="card-body">
        <h5><?php echo $row['name']; ?></h5>
        <p class="price">Rs. <?php echo $row['price']; ?></p>
        <button class="btn btn-buy" data-bs-toggle="modal" data-bs-target="#productModal<?php echo $row['id']; ?>">View Details</button>
      </div>
    </div>
  </div>

  <!-- Product Modal -->
  <div class="modal fade" id="productModal<?php echo $row['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><?php echo $row['name']; ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <img src="../uploads/<?php echo $row['image']; ?>" class="img-fluid rounded mb-3">
          <p><?php echo $row['description']; ?></p>
          <p class="price">Rs. <?php echo $row['price']; ?></p>
        </div>
        <div class="modal-footer">
          <form class="add-to-cart-form d-flex align-items-center">
            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
            <div class="input-group me-3" style="width:120px;">
              <input type="number" name="quantity" value="1" min="1" class="form-control text-center">
            </div>
            <button type="button" class="btn btn-buy add-to-cart-btn">Add to Cart</button>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
</div>

<!-- Pagination -->
<?php if($total_pages > 1): ?>
<nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center mt-4">
    <?php for($i=1; $i<=$total_pages; $i++): ?>
      <li class="page-item <?php if($i==$page) echo 'active'; ?>">
        <a class="page-link" href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>">
          <?php echo $i; ?>
        </a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>
<?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Add to Cart
document.querySelectorAll('.add-to-cart-btn').forEach(button => {
  button.addEventListener('click', async function() {
    const form = this.closest('.add-to-cart-form');
    const formData = new FormData(form);
    try {
      const res = await fetch('add_to_cart.php', { method:'POST', body: formData });
      const text = await res.text();
      let data;
      try { data = JSON.parse(text); } catch(err) { console.error(text); Swal.fire('Error','Server returned invalid response.','error'); return; }

      if(res.ok && data.success) {
        if(typeof data.count!=='undefined') document.getElementById('cart-count').innerText = data.count;
        else updateCartCount();
        Swal.fire({
          title:'Added to Cart!',
          text:data.message||'Item added.',
          icon:'success',
          showCancelButton:true,
          confirmButtonText:'Go to Cart',
          cancelButtonText:'Continue Shopping',
          confirmButtonColor:'#4b0e19',
          cancelButtonColor:'#888'
        }).then((result)=>{ if(result.isConfirmed) window.location.href='viewCart.php'; });
      } else {
        if(res.status===401 || data.message?.toLowerCase()?.includes('login')) {
          Swal.fire('Login required', data.message||'Please login to add items.','warning').then(()=>window.location.href='login.php');
        } else Swal.fire('Error', data.message||'Something went wrong.','error');
      }
    } catch(err) { console.error(err); Swal.fire('Error','Network or server error.','error'); }
  });
});

function updateCartCount() {
  fetch('cart_count.php')
  .then(res=>res.json())
  .then(data=>{
    const count=(data && (data.count ?? data)) || 0;
    document.getElementById('cart-count').innerText = count;
  }).catch(err=>console.error(err));
}
updateCartCount();
</script>
 <!-- Footer -->
  <footer class="site-footer">
    <div class="container text-center">
      <div>Copyright © 2025 Andaz-e-Bayan | All rights reserved</div>
      <div class="footer-links">
        <a href="#">Urdu Poetry</a> | <a href="#">Bait Bazi</a> | <a href="#">Shop</a> | <a href="#">Contact</a>
      </div>
    </div>
  </footer>
</body>
</html>
