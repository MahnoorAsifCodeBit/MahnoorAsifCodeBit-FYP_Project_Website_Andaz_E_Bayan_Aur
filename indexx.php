<?php
session_start();
include 'config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT name, profile_pic FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    $user_name = $user['name'];

if (!empty($user['profile_pic'])) {
    if (str_starts_with($user['profile_pic'], 'uploads/')) {
        $profile_pic = 'user/' . $user['profile_pic']; 
    } else {
        $profile_pic = 'user/images/' . $user['profile_pic'];
    }
} else {
    $profile_pic = 'user/images/default.png'; // fallback
}

} else {
    $user_name = "Guest";
    $profile_pic = 'user/images/nodp.png';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Andaz-e-Bayan — Urdu Poetry & AR</title>
  <link rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="landing.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu:wght@400;600&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="landing.css">
    <link href="https://fonts.googleapis.com/css2?family=Gulzar&display=swap" rel="stylesheet">

    <style>
      .btn-maroon {
      background-color: #800000; /* Maroon color code */
      color: #ffffff; /* White text for contrast */
      border-color: #800000; /* Optional: matching border color */
    }

    .btn-maroon:hover,
    .btn-maroon:focus {
      background-color: #660000; /* Slightly darker maroon on hover/focus */
      border-color: #660000;
    }
    .img{
      width: 100px;
      height: 380px;
    }

        .imgs{
      width: 400px;
      height: 350px;
      margin-left: 100px;
      margin-top: 10px;
    }
    </style>
  <!-- Model Viewer for AR -->
  <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
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
</head>
<body>
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="user/images/white.png" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a></li>
        <li class="nav-item"><a class="nav-link" href="user/userCategory.php"><i class="fas fa-feather-alt"></i> Category</a></li>
        <li class="nav-item"><a class="nav-link" href="user/customization.php"><i class="fas fa-cogs"></i> Customization</a></li>
        <li class="nav-item"><a class="nav-link" href="user/challenges.php"><i class="fas fa-trophy"></i> Challenges</a></li>
        <li class="nav-item"><a class="nav-link" href="user/e-commerce.php"><i class="fas fa-shopping-cart"></i> Merchandise</a></li>
        <li class="nav-item"><a class="nav-link" href="user/team.php"><i class="fas fa-people-arrows"></i> Team </a></li>
        <li class="nav-item dropdown">
          <div id="google_translate_element"></div>
        </li>
      </ul>
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
            <img src="<?php echo $profile_pic; ?>" class="nav-image"> &nbsp; <?php echo htmlspecialchars($user_name);?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="user/viewProfile.php">Profile</a></li>
            <li><a class="dropdown-item" href="userLogout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Hero Section -->
<section class="hero">
  <div class="hero-overlay"></div>
  <div class="container hero-grid">
    
    <!-- Hero Text -->
    <div class="hero-text">
      <h1 class="hero-heading-urdu">
        کیوں زیاں کار بنوں سود فراموش رہوں <br>
        فکرِ فردا نہ کروں محوِ غمِ دوش رہوں
      </h1>
      <p class="lead">
        Dive into timeless verses, challenge your friends in <strong>Bait Bazi</strong>, 
        and let Urdu calligraphy breathe life through <strong>Augmented Reality</strong>.
      </p>
      <div class="cta-row hero-heading-urdu">
        <a class="btn btn-primary" href="user/userCategory.php">اشعار دیکھیں</a>
        <a class="btn btn-secondary" href="user/challenges.php">بیت بازی</a>
        <a class="btn btn-ghost btn-maroon" href="user/customization.php">خطاطی</a>
      </div>
    </div>

    <!-- Hero Visual / AR -->
    <div class="hero-visual">
      <div class="ar-feature">
        <h3 class="accent"> Try Poetry in AR</h3>
        <div class="img"><img src="user/images/qr.png" class="imgs"></div>
        <!-- <model-viewer 
          src="https://modelviewer.dev/shared-assets/models/RobotExpressive.glb" 
          alt="Poetry AR"
          ar 
          ar-modes="webxr scene-viewer quick-look"
          camera-controls 
          autoplay
          style="width: 100%; height: 350px; border-radius: 12px; background: #fff;">
        </model-viewer> -->
        <p class="ar-note">Bring Urdu verses to life in your own space.</p>
      </div>

      <!-- Rotating Urdu Couplets -->
      <div class="rotator">
        <div class="rot-item">“اگرچہ دنیا بدل گئی، شاعری باقی ہے” — غالب</div>
        <div class="rot-item">“دل کو خوش رکھو، لمحے قیمتی ہیں” — فیض احمد فیض</div>
        <div class="rot-item">“منتخب اشعار روزانہ”</div>
        <div class="rot-item">📅 مقابلہ: Bait Bazi — 5 اکتوبر</div>
      </div>
    </div>
  </div>
</section>

<!-- Highlights -->
<section class="highlights">
  <h2 class="section-title" style="color:white;">Services</h2>
  <div class="cards">

    <!-- Card 1 -->
    <article class="card">
      <div class="card-content">
        <h3>Urdu Poetry Collections</h3>
        <p>Curated couplets, ghazals, and nazms — search by poet, mood, or form.</p>
        <a class="small-btn" href="user/userCategory.php">Explore Collections</a>
      </div>
    </article>

    <!-- Card 2 -->
    <article class="card special">
      <div class="card-content">
        <h3>Bait Bazi Arena</h3>
        <p>Join real-time bait bazi matches, create teams, and climb the leaderboard.</p>
        <ul class="match-info">
          <li><a class="small-btn join-now" href="user/challenges.php">Join Now</a></li>
        </ul>
      </div>
    </article>

    <!-- Card 3 -->
    <article class="card">
      <div class="card-content">
        <h3>Customize Calligraphy</h3>
        <p>Type your verse, choose a style, and order prints or merchandise.</p>
        <a class="small-btn" href="user/e-commerce.php">Start Creating</a>
      </div>
    </article>

  </div>
</section>

<!-- Products Section -->
<section id="shop" class="py-5">
  <div class="container">
    <h2 class="section-title shop-title ">Shop Our Urdu Calligraphy Products</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="product-card position-relative">
          <span class="qr-badge">AR QR</span>
          <img src="user/images/mug.jpg" alt="Mug" class="product-img" />
          <div class="p-3">
            <h5>Mugs with Calligaphy Poetry</h5>
            <p>Unique mugs featuring your calligraphy and AR QR codes.</p>
            <a href="user/e-commerce.php"><button class="btn btn-maroon">Buy Now</button></a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-card position-relative">
          <span class="qr-badge">AR QR</span>
          <img src="user/images/tote.jpg" alt="Tote Bag" class="product-img" />
          <div class="p-3">
            <h5>Calligraphy Tote Bags</h5>
            <p>Stylish tote bags with calligraphy & AR QR codes.</p>
            <a href="user/e-commerce.php"><button class="btn btn-maroon">Buy Now</button></a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="product-card position-relative">
          <span class="qr-badge">AR QR</span>
          <img src="user/images/cloth.jpeg" alt="T-Shirt" class="product-img" />
          <div class="p-3">
            <h5>Andaz-e-Libās</h5>
            <p>Wear your poetry with pride, calligraphy & AR QR included.</p>
            <a href="user/e-commerce.php"><button class="btn btn-maroon">Buy Now</button></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
  
<!-- Poets -->
<section class="highlights container">
  <h2 class="section-title">Featured Poets</h2>
  <div class="poet-grid">
    <div class="poet">
      <div class="poet-photo"><img src="user/images/faiz.jpeg" alt=""></div>
      <h4>Faiz Ahmad Faiz</h4>
      <p>دل تو بہہ جاتا ہے، جب کوئی ایک شعر یاد آتا ہے۔</p>
    </div>
    <div class="poet">
      <div class="poet-photo"><img src="user/images/parveen.png" alt=""></div>
      <h4>Parveen Shakir</h4>
      <p>خوبصورت اور نرم انداز میں شاعری کا اظہار۔</p>
    </div>
    <div class="poet">
      <div class="poet-photo"><img src="user/images/taki.jpeg" alt=""></div>
      <h4>میر تقی میر</h4>
      <p>کلاسک غزل کے شاہکار اور لاجواب الفاظ۔</p>
    </div>
  </div>
</section>


  <!-- Footer -->
  <footer class="site-footer">
    <div class="container">
      <div>Copyright © 2025 Andaz-e-Bayan | All rights reserved</div>
      <div class="footer-links">
        <a href="#">Urdu Poetry</a> | <a href="#">Bait Bazi</a> | <a href="#">Shop</a> | <a href="#">Contact</a>
      </div>
    </div>
  </footer>
<!-- Bootstrap JS Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
