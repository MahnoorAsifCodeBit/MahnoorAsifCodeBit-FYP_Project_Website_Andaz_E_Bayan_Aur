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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>                اندازِ بیاں اور </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="landing.css">
    <link href="https://fonts.googleapis.com/css2?family=Gulzar&display=swap" rel="stylesheet">

    <style>
  @import url('https://fonts.googleapis.com/css2?family=Gulzar&display=swap');
  @import url('https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&display=swap');

  
.navbar {
  position: relative; /* Sticks to the top */
  top: 0;
  left: 0;
  width: 99vw; /* Full width */
  height: 70px;
  z-index: 99999; /* Stays on top of other content */
  background: #4b0e19; /* Semi-transparent maroon */
  backdrop-filter: blur(10px); /* Glass effect */
  padding: 10px 20px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
  display: flex;
  align-items: center;
  justify-content: space-between;
}

/* Logo styling: visible, well-sized */
.navbar-brand img {
  max-height: 80px;
  width: auto;
  object-fit: cover;
  margin-right: 10px;
}


/* Center all nav items (except user) */
.navbar-nav {
  margin: 0 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 40px;
}

/* Nav link styling */
.navbar-nav .nav-link {
  color: white !important;
  font-size: 16px;
  font-weight: 600;
  padding: 6px 12px;
  transition: all 0.3s ease-in-out;
}

/* Hover effect on links */
.navbar-nav .nav-link:hover, 
.navbar-nav .nav-link:focus {
  color: #FFDAB9 !important;
  text-shadow: 0 0 6px rgba(255, 255, 255, 0.5);
  border-bottom: 2px solid white;
}

/* Dropdown menu styling */
.navbar-nav .dropdown-menu {
  background-color: #4b0e19;
  border: none;
}

/* Dropdown item styles */
.navbar-nav .dropdown-item {
  color: white;
  padding: 10px 20px;
  transition: all 0.3s ease-in-out;
}

/* Dropdown item hover effect */
.navbar-nav .dropdown-item:hover {
  background-color: #6c0000;
  color: #FFDAB9;
  padding-left: 25px;
}

/* User avatar image styling */
.nav-image {
  height: 50px;
  width: 50px;
  border-radius: 50%;
  object-fit: cover;
  margin-left: 8px;
  border: 1px solid #4b0e19;
}

/* Keep avatar/user menu on right */
.navbar-collapse {
  justify-content: space-between;
  align-items: center;
}

/* Optional: white caret for dropdown */
.navbar-light .navbar-toggler-icon {
  filter: invert(1);
}


/* Logout button */
.logout-btn {
  display: inline-block;
  margin-top: 7px;
  margin-right: 15px;
  background: rgb(134, 114, 114);
  color: #fff;
  padding: 10px 26px;
  border-radius: 10px;
  text-decoration: none;
  font-size: 16px;
  transition: all 0.3s;
}
.logout-btn:hover {
  background: #b30000;
  transform: translateY(-5px);
  padding: 20px 48px;
  font-size: large;
  font-weight: bold;
}

.hero-section {
    position: relative;
    background: linear-gradient(rgba(0, 0, 0, 0.68), rgba(0, 0, 0, 0.51)),
                url('user/images/back.jpg') no-repeat center center/cover;
    color: white;
    padding: 80px 0;
}


  .hero-heading-urdu {
    font-family: 'Gulzar', serif;
    font-size: 2.5rem;
    direction: rtl;
    line-height: 1.8;
    text-align: right;
  }

    .hero-heading-urdu1 {
    font-family: 'Noto Nastaliq Urdu', serif;
    font-size: 2.5rem;
    direction: rtl;
    line-height: 1.8;
    text-align: right;
  }


  .lead {
    font-size: 1.1rem;
    margin-top: 15px;
  }

  .btn-maroon {
    background-color: #4b0e19;
    color: #fff;
    border-radius: 30px;
    padding: 10px 24px;
    transition: 0.3s;
  }

  .btn-maroon:hover {
    background-color: #741829ff;
    transform: translateY(-2px);
  }

 
  /* Cube Sec */
.heading{
    text-align: center;
}

.title{
  color: #4b0e19;
  text-align: center;
}
.cube-container {
  position: relative;
  width: 400px;
  height: 400px;
  perspective: 1200px;
  margin-bottom: 40px;
  margin-top: 130px;
  margin-left:40%;
}

/* Rotating Cube Styles */
.cube {
  position: relative;
  width: 90%;
  height: 90%;
  transform-style: preserve-3d;
  animation: rotateCube 20s infinite linear;
}

.cube-container {
  margin: 130px auto 40px;
}

.face {
  position: absolute;
  width: 90%;
  height: 90%;
  background-color: #4b0e19; /* Maroon background */
  color: white;
  border-radius: 10px;
  padding: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: transform 1s;
}

.front  { transform: rotateY(  0deg) translateZ(200px); }
.back   { transform: rotateY(180deg) translateZ(200px); }
.left   { transform: rotateY(-90deg) translateZ(200px); }
.right  { transform: rotateY( 90deg) translateZ(200px); }
.top    { transform: rotateX( 90deg) translateZ(200px); }
.bottom { transform: rotateX(-90deg) translateZ(200px); }

.cube .content {
  text-align: center;
  max-width: 300px;
}

.poem-title {
  font-size: 1.6em;
  font-weight: 700;
  margin-bottom: 10px;
}

.poem-snippet {
  font-size: 1.1em;
  margin-bottom: 15px;
}

.cube-container:hover .cube {
  animation-play-state: paused;
}

.btn-read {
  font-size: 1.1em;
  font-weight: bold;
  color: #4b0e19;
  text-decoration: none;
  border: 2px solid #4b0e19;
  padding: 10px 20px;
  border-radius: 5px;
  transition: background-color 0.3s, color 0.3s;
}

.btn-read:hover {
  background-color: #4b0e19;
  color: white;
}

@keyframes rotateCube {
  0% { transform: rotateY(0deg); }
  25% { transform: rotateY(90deg); }
  50% { transform: rotateY(180deg); }
  75% { transform: rotateY(270deg); }
  100% { transform: rotateY(360deg); }
}

/* Responsiveness */
@media (max-width: 600px) {
  .cube-container {
    width: 300px;
    height: 300px;
  }
  .cube .content {
    font-size: 0.9em;
  }
}


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

/* Lang switcher */
.goog-te-gadget img {
  display: none; /* Hide Google branding image */
}
.goog-te-gadget-simple {
  background-color: #4b0e19 !important;
  border: none !important;
  color: white !important;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
}
.goog-te-gadget-simple:hover {
  background-color: #4b0e19 !important;
}

.goog-te-gadget-simple .VIpgJd-ZVi9od-xl07Ob-lTBxed {
  color: rgb(255, 255, 255) !important;
  font-size: 16px;
  font-weight: bold;
}


    </style>
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
<section id="hero" class="hero-section">
  <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">

      <!-- Slide 1 -->
      <div class="carousel-item active" style="min-height: 90vh;">
        <div class="container h-100 d-flex align-items-center">
          <div class="row w-100 align-items-center">
            <!-- Text Column -->
            <div class="col-lg-6 text-lg-end text-center d-flex flex-column justify-content-center" style="min-height: 70vh;">
              <h1 class="hero-heading-urdu">کیوں زیاں کار بنوں سود فراموش رہوں</h1>
              <h1 class="hero-heading-urdu">فکر فردا نہ کروں محو غم دوش رہوں</h1>
              <p class="lead mt-3">Customize calligraphy, and shop unique products that make your poetry unforgettable.</p>
              <div class="mt-3 hero-heading-urdu">
                <a href="user/customization.php" class="btn btn-maroon btn-lg me-2">
                  <i class="fas fa-paint-brush me-2"></i> اردو خطاطی
                </a>
              </div>
            </div>
            <!-- Image Column -->
            <div class="col-lg-6 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
              <img src="user/images/iqbal.jpg" alt="Calligraphy Preview" class="img-fluid rounded shadow" style="max-height: 500px; object-fit: cover;" />
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 2 -->
      <div class="carousel-item" style="min-height: 90vh;">
        <div class="container h-100 d-flex align-items-center">
          <div class="row w-100 align-items-center">
            <div class="col-lg-6 text-lg-end text-center d-flex flex-column justify-content-center" style="min-height: 70vh;">
              <h1 class="hero-heading-urdu">ہر شعر ایک پہیلی، ہر جواب ایک راز</h1>
              <h1 class="hero-heading-urdu">بیت بازی کے رنگ ہیں دل کے خاص ساز</h1>
              <p class="lead mt-3">Join the Bait-Baazi challenge and let your Urdu poetry shine</p>
              <div class="mt-3 hero-heading-urdu">
                <a href="user/customization.php" class="btn btn-maroon btn-lg me-2">
                <i class="fas fa-medal me-2"></i> بیت بازی

                </a>
              </div>

            </div>
            <div class="col-lg-6 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
              <img src="user/images/bait.jpg" alt="Poetry Calligraphy" class="img-fluid rounded shadow" style="max-height: 500px; object-fit: cover;" />
            </div>
          </div>
        </div>
      </div>

      
      <!-- Slide 3 -->
      <div class="carousel-item" style="min-height: 90vh;">
        <div class="container h-100 d-flex align-items-center">
          <div class="row w-100 align-items-center">
            <div class="col-lg-6 text-lg-end text-center d-flex flex-column justify-content-center" style="min-height: 70vh;">
              <h1 class="hero-heading-urdu"> اردو خطاطی کے ساتھ ہر چیز بنائیں خاص</h1>
              <p class="lead mt-3">Shop poetry-inspired gifts that tell your story.</p>
              <div class="mt-3 hero-heading-urdu">
                  <a href="user/e-commerce.php" class="btn btn-outline-light btn-lg">
                  <i class="fas fa-shopping-cart me-2"></i> ابھی خریدیں
                </a>
              </div>

            </div>
            <div class="col-lg-6 d-flex justify-content-center align-items-center" style="min-height: 70vh;">
              <img src="user/images/model.jpg" alt="Poetry Calligraphy" class="img-fluid rounded shadow" style="max-height: 500px; object-fit: cover;" />
            </div>
          </div>
        </div>
      </div>

      

    <!-- Slide 4 -->
      <div class="carousel-item" style="min-height: 90vh;">
        <div class="container h-100 d-flex align-items-center">
          <div class="row w-100 align-items-center">
            <div class="col-lg-6 text-lg-end text-center d-flex flex-column justify-content-center" style="min-height: 70vh;">
              <h1 class="hero-heading-urdu"> ستاروں سے آگے جہاں اور بھی ہیں</h1>
               <h1 class="hero-heading-urdu"> ابھی عشق کے امتحاں اور بھی ہیں</h1>
              <p class="lead mt-3">Expeirnce AR in Urdu Poetry!</p>
              <!-- <div class="mt-3">
                  <a href="user/team.php" class="btn btn-outline-light btn-lg">
                  <i class="fas  fa-laptop-code me-2"></i> Team
                </a>
              </div> -->

            </div>
<div class="col-lg-6 d-flex justify-content-center align-items-center position-relative" style="min-height: 50vh;">
  <img src="user/images/qr.png" 
       alt="Poetry Calligraphy" 
       class="img-fluid rounded shadow position-absolute"
       style="max-height: 350px; width: auto; object-fit: contain; top: 50%; transform: translateY(-60%);">
</div>


        </div>
      </div>

    </div>

    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>

    <!-- Indicators -->
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
    </div>

  </div>
</section>
 
<!-- Features Section -->
<section id="customize" class="py-5" style="background: linear-gradient(to bottom, #fefefe, #f2f2f2);">
  <div class="container">
    <h2 class="section-title text-center mb-5" style="font-family: 'Jameel Noori Nastaleeq', cursive; font-size: 2.8rem; color: #800000;">
      Celebrate Urdu Poetry & Calligraphy
    </h2>
    <div class="row g-4">

      <!-- Feature Card 1: Calligraphy Customization -->
      <div class="col-md-4">
        <div class="feature-box text-center p-4 h-100 glass-card">
          <div class="feature-icon mb-3">
            <i class="fas fa-paint-brush fa-2x" style="color: #800000;"></i>
          </div>
          <h5 class="mb-3" style="font-family: 'Jameel Noori Nastaleeq', cursive;">
            <a href="user/customization.php" class="feature-link text-decoration-none">Calligraphy Customization</a>
          </h5>
          <p style="font-size: 0.95rem;">
              <p class="hero-heading-urdu1" style="font-size: 0.95rem; text-align:center"> خطاطی سے دل کی بات نکلتی ہے  
              — میر تقی میر</p><br>
            Customize your poetry frames with elegant calligraphy fonts. <br>
            <span style="font-style: italic; color: #555;">“Let every word flow beautifully onto your canvas.”</span>
          </p>
        </div>
      </div>

      <!-- Feature Card 2: AR QR Code Meanings -->
      <div class="col-md-4">
        <div class="feature-box text-center p-4 h-100 glass-card">
          <div class="feature-icon mb-3">
            <i class="fas fa-qrcode fa-2x" style="color: #800000;"></i>
          </div>
          <h5 class="mb-3" style="font-family: 'Jameel Noori Nastaleeq', cursive;">
            <a href="user/customization.php" class="feature-link text-decoration-none">AR QR Code Meanings</a>
          </h5>
          <p style="font-size: 0.95rem;" class="he">
            <p class="hero-heading-urdu1" style="font-size: 0.95rem; text-align:center"> لفظوں کی چھپی حقیقت جاننا بھی فن ہے  
              — احمد فراز
          </p><br>
            Scan poetry with AR and explore hidden meanings in 3D. <br>
            <span style="font-style: italic; color: #555;">“Discover the soul behind every word.”</span>
          </p>
        </div>
      </div>

      <!-- Feature Card 3: E-Commerce Store -->
      <div class="col-md-4">
        <div class="feature-box text-center p-4 h-100 glass-card">
          <div class="feature-icon mb-3">
            <i class="fas fa-store fa-2x" style="color: #800000;"></i>
          </div>
          <h5 class="mb-3" style="font-family: 'Jameel Noori Nastaleeq', cursive;">
            <a href="user/e-commerce.php" class="feature-link text-decoration-none">E-Commerce Store</a>
          </h5>
          <p style="font-size: 0.95rem;">
          <p class="hero-heading-urdu1" style="font-size: 0.95rem; text-align:center"> محبتیں بیچنی نہیں، مگر لفظ بیچ سکتے ہیں  
              — پروین شاکر
          </p><br>
            Shop mugs, stoles, and totes adorned with beautiful Urdu calligraphy. <br>
            <span style="font-style: italic; color: #555;">“Bring poetry to life, one product at a time.”</span>
          </p>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- AR Preview Section -->
<section id="ar-preview">
  <div class="container">
    <h2 class="section-title text-center mb-5">Experience AR Poetry</h2>
    
    <div class="row justify-content-center g-4">
      <!-- AR Image Card 1 -->
      <div class="col-md-4">
        <div class="ar-card">
          <img src="user/images/ar1.png" alt="AR Preview 1" class="img-fluid" />
        </div>
      </div>
      <!-- AR Image Card 2 -->
      <div class="col-md-4">
        <div class="ar-card">
          <img src="user/images/ar6.png" alt="AR Preview 2" class="img-fluid" />
        </div>
      </div>
      <!-- AR Image Card 3 -->
      <div class="col-md-4">
        <div class="ar-card">
          <img src="user/images/ar5.png" alt="AR Preview 3" class="img-fluid" />
        </div>
      </div>
    </div>

    <p class="ar-description mt-4">
      Scan the QR code on your customized frame or product to see difficult words come alive in 3D Augmented Reality right on your phone.
    </p>
    
    <!-- Call to Action Button -->
    <div class="cta-container mt-5">
      <a href="user/customization.php" class="btn btn-maroon">Explore AR Feature</a>
    </div>
  </div>
</section>

<!-- Products Section -->
<section id="shop" class="py-5">
  <div class="container">
<h2 class="section-title text-center mb-5" style="font-family: 'Jameel Noori Nastaleeq', cursive; font-size: 2.8rem; color: #800000;">
Shop our Calligraphy Products
</h2>
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
            <p>Wear your poetry with pride - calligraphy & AR QR included.</p>
            <a href="user/e-commerce.php"><button class="btn btn-maroon">Buy Now</button></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

     <!-- Cube Section -->
   <h2 class="section-title text-center mb-5" style="font-family: 'Jameel Noori Nastaleeq', cursive; font-size: 2.8rem; color: #800000;">
    The Magic of Words
   </h2>

  <section class="cube-container">
    <div class="cube">
      <div class="face front">
        <div class="content">
          <h2 class="poem-title">Poem 1: Ghalib's Verses</h2>
          <p class="poem-snippet">اگ رہا ہے درو دیوار سے سبزہ غالب
            <br>
          ...ہم بیاباں میں ہیں اور گھر میں بہار آئی ہے </p>
          <a href="#" class="btn-read">Read More</a>
        </div>
      </div>
      <div class="face back">
        <div class="content">
          <h2 class="poem-title">Poem 2: Iqbal's Vision</h2>
          <p class="poem-snippet">ایک مدت سے تری یاد بھی آئی نہ ہمیں
            <br>...اور ہم بھول گئے ہوں تجھے ایسا بھی نہیں</p>
          <!-- <a href="#" class="btn-read">Read More</a> -->
        </div>
      </div>
      <div class="face left">
        <div class="content">
          <h2 class="poem-title">Poem 3: Rumi's Wisdom</h2>
          <p class="poem-snippet">ماں باپ کے ساتھ آپکا سلوک ایسی کہانی ہے
            <br>...جو لکھتے آپ ہیں لیکن آپ کی اولاد آپ کو پڑھ کے سٌناتی ہے۔</p>
          <!-- <a href="#" class="btn-read">Read More</a> -->
        </div>
      </div>
      <div class="face right">
        <div class="content">
          <h2 class="poem-title">Poem 4: Faiz's Passion</h2>
          <p class="poem-snippet">مجھ سے پہلی سی محبت مری محبوب نہ مانگ
            <br>...میں نے سمجھا تھا کہ تو ہے تو درخشاں ہے حیات</p>
          <!-- <a href="#" class="btn-read">Read More</a> -->
        </div>
      </div>
      <div class="face top">
        <div class="content">
          <h2 class="poem-title">Poem 5: Ahmed Faraz</h2>
          <p class="poem-snippet">چپ چاپ اپنی آگ میں جلتے رہو فرازؔ
            <br>...دنیا تو عرض حال سے بے آبرو کرے</p>
          <!-- <a href="#" class="btn-read">Read More</a> -->
        </div>
      </div>
      <div class="face bottom">
        <div class="content">
          <h2 class="poem-title">Poem 6: Parveen Shakir</h2>
          <p class="poem-snippet">وہ تو خوش بو ہے ہواؤں میں بکھر جائے گا
            <br>...مسئلہ پھول کا ہے پھول کدھر جائے گا</p>
          <!-- <a href="#" class="btn-read">Read More</a> -->
        </div>
      </div>
    </div>
  </section>
 <!-- Footer -->
  <footer class="site-footer text-center">
      <div>Copyright © 2025 Andaz-e-Bayan | All rights reserved</div>
      <div class="footer-links">
        <a href="user/userCategory.php">Urdu Poetry</a> | <a href="user/challenges.php">Bait Bazi</a> | 
        <a href="user/e-commerce.php">Shop</a> | <a href="user/team.php">Contact</a>
      </div>
  </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>