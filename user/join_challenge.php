<?php
include '../config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../userlogin.php");
    exit();
}


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT name, profile_pic FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    $user_name = $user['name'];

    // Now check if profile_pic starts with 'uploads/' or is just a file name
    if (!empty($user['profile_pic'])) {
        if (str_starts_with($user['profile_pic'], 'uploads/')) {
            $profile_pic = '/andazebayan-admin/user/' . $user['profile_pic']; 
        } else {
            $profile_pic = '/andazebayan-admin/user/images/' . $user['profile_pic'];
        }
    } else {
        $profile_pic =  'nodp.png';
    }
} else {
    $user_name = "Guest";
    $profile_pic = 'images/nodp.png';
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Challenge ID is missing.");
}

$challenge_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM challenges WHERE id = $challenge_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}

$challenge = mysqli_fetch_assoc($result);

if (!$challenge) {
    die("Error: Challenge not found.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entry_text = mysqli_real_escape_string($conn, $_POST['entry_text']);

    $insertQuery = "INSERT INTO challenge_participants (challenge_id, user_id, entry_text) 
                    VALUES ($challenge_id, $user_id, '$entry_text')";

    if (mysqli_query($conn, $insertQuery)) {
        echo "<script>alert('Your entry has been submitted!'); window.location='challenges.php';</script>";
    } else {
        echo "Error submitting entry: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Join Challenge - <?= htmlspecialchars($challenge['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

body {
  font-family: 'Poppins', sans-serif;
  background: radial-gradient(circle at top, #fffbe9, #f9f1d6, #f3e2b8);
  background-attachment: fixed;   /* <— important */
  background-repeat: no-repeat;
  background-size: cover;         /* ensures full screen */
  min-height: 100vh;
  color: #2c1810;
  margin: 0;
  overflow-x: hidden;
}
.challenge-section {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  min-height: calc(100vh - 80px);
  padding-top: 80px;
}

.progress-ring {
  margin-top: 10px;
  transition: transform 0.3s ease;
}

.progress-ring:hover {
  transform: scale(1.05);
}

@media (max-width: 992px) {
  .container-fluid {
    flex-direction: column !important;
    align-items: center !important;
  }

  .challenge-desc {
    text-align: center !important;
    margin-bottom: 20px;
  }

  .form-container {
    width: 90% !important;
  }
}


    /* --- Hero / Arena Section --- */
    .challenge-section {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 110px 20px 60px;
      position: relative;
      text-align: center;
    }

    /* Animated gradient ring */
    .challenge-section::before {
      content: "";
      position: absolute;
      top: -10%;
      left: 50%;
      transform: translateX(-50%);
      width: 800px;
      height: 800px;
      border-radius: 50%;
      background: conic-gradient(from 0deg, rgba(255,215,0,0.2), rgba(123,17,19,0.2), rgba(255,215,0,0.2));
      animation: rotate 10s linear infinite;
      z-index: 0;
      filter: blur(80px);
    }

    @keyframes rotate {
      from { transform: translateX(-50%) rotate(0deg); }
      to { transform: translateX(-50%) rotate(360deg); }
    }

    h2 {
      font-size: 2.8rem;
      font-weight: 800;
      color: #7b1113;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      position: relative;
      z-index: 1;
      margin-bottom: 20px;
    }

    h2 i {
      color: #e0a100;
      animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.15); opacity: 0.8; }
    }

.challenge-desc {
  max-width: 720px;
  font-size: 1.05rem;
  color: #4b2e25;
  margin-bottom: 45px;
  z-index: 1;
  position: relative;
  direction: rtl;          /* Urdu reads right-to-left */
  text-align: right;       /* Align text properly for Urdu */
  margin-right: auto;
  margin-left: auto;       /* Keep it centered block-wise */
  line-height: 1.8;        /* Easier to read Urdu */
}

.urdu-desc p {
  text-align: center;        /* Title also right-aligned */
  direction: rtl;
}

    /* Glowing underline */
    h2::after {
      content: "";
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 120px;
      height: 4px;
      border-radius: 3px;
      background: linear-gradient(90deg, #ffb700, #ff6600);
      box-shadow: 0 0 10px rgba(255, 165, 0, 0.6);
      animation: glow 2s infinite alternate;
    }

    @keyframes glow {
      from { opacity: 0.6; box-shadow: 0 0 6px rgba(255,165,0,0.4); }
      to { opacity: 1; box-shadow: 0 0 18px rgba(255,165,0,0.8); }
    }

    /* --- Challenge Entry Console --- */
    .form-container {
      background: linear-gradient(135deg, rgba(255,255,255,0.85), rgba(255,255,255,0.75));
      border: 2px solid rgba(123,17,19,0.25);
      border-radius: 18px;
      box-shadow: 0 12px 30px rgba(123,17,19,0.25), inset 0 0 12px rgba(255, 204, 0, 0.15);
      width: 440px;
      padding: 40px 35px;
      position: relative;
      transition: 0.4s ease;
      z-index: 2;
    }

    .form-container:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 40px rgba(123,17,19,0.25), inset 0 0 18px rgba(255, 204, 0, 0.25);
    }

    label {
      display: block;
      font-weight: 600;
      color: #7b1113;
      font-size: 1rem;
      text-align: left;
      margin-bottom: 8px;
    }

    textarea {
      width: 100%;
      height: 130px;
      border: 2px solid rgba(123,17,19,0.3);
      border-radius: 10px;
      padding: 10px;
      font-size: 15px;
      color: #3d2c25;
      background: rgba(255, 255, 255, 0.9);
      outline: none;
      transition: all 0.3s ease;
      resize: none;
    }

    textarea:focus {
      border-color: #d4a200;
      box-shadow: 0 0 10px rgba(212, 162, 0, 0.5);
    }

    .submit-btn {
      margin-top: 22px;
      background: linear-gradient(90deg, #ffb600, #ff6600);
      color: #fff;
      font-weight: 700;
      border: none;
      border-radius: 10px;
      padding: 12px;
      width: 100%;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease;
      font-size: 1rem;
      position: relative;
      overflow: hidden;
    }

    .submit-btn:hover {
      background: linear-gradient(90deg, #ffcc00, #e65c00);
      transform: translateY(-3px);
      box-shadow: 0 4px 16px rgba(255, 140, 0, 0.3);
    }

    .submit-btn:active {
      transform: scale(0.97);
    }

    /* Subtle particle sparks */
    .spark {
      position: absolute;
      background: radial-gradient(circle, rgba(255,200,0,0.7), transparent);
      border-radius: 50%;
      animation: float 5s linear infinite;
      opacity: 0.8;
    }

    @keyframes float {
      from { transform: translateY(0) scale(1); opacity: 0.8; }
      to { transform: translateY(-300px) scale(0.5); opacity: 0; }
    }

    @media (max-width: 768px) {
      .form-container { width: 90%; }
      h2 { font-size: 2rem; }
    }

/* Navbar background color and height adjustments */

:root {
  --maroon: #800000;
  --maroon-dark: #6c0000;
  --hover-light: #d7ccc8;
}


@media (max-width: 768px) {
  .nav-home a {
    font-size: 18px;
    margin: 10px;
  }
}

body{
    padding: 0px;
    margin: 0px;
}

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
      <img src="images/white.png" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="../index.php"><i class="fas fa-home"></i> Home</a></li>
        <li class="nav-item"><a class="nav-link" href="userCategory.php"><i class="fas fa-feather-alt"></i> Category</a></li>
        <li class="nav-item"><a class="nav-link" href="customization.php"><i class="fas fa-cogs"></i> Customization</a></li>
        <li class="nav-item"><a class="nav-link" href="challenges.php"><i class="fas fa-trophy"></i> Challenges</a></li>
        <li class="nav-item"><a class="nav-link" href="e-commerce.php"><i class="fas fa-shopping-cart"></i> Merchandise</a></li>
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
            <li><a class="dropdown-item" href="viewProfile.php">Profile</a></li>
            <li><a class="dropdown-item" href="../userLogout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<section class="challenge-section">
  <div class="container-fluid d-flex flex-wrap align-items-start justify-content-center mt-4">
    
    <!-- Left Side: Description -->
    <div class="col-lg-6 col-md-12 mb-4 px-4 urdu-desc">
      <h2><i class="fas fa-trophy"></i> <?= htmlspecialchars($challenge['title']); ?></h2>
      <p class="challenge-desc"><?= nl2br(htmlspecialchars($challenge['description'])); ?></p>
    </div>

    <!-- Right Side: Form + Word Counter -->
    <div class="col-lg-5 col-md-12 d-flex flex-column align-items-center justify-content-start px-4">
      <div class="form-container mb-3 w-100">
        <form method="POST">
          <label><i class="fas fa-pen-nib"></i> Submit Your Poetry Entry</label>
          <textarea id="entry_text" name="entry_text" placeholder="Type your challenge entry here..." required></textarea>
          <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Submit Entry</button>
        </form>
      </div>

      <!-- Word Counter -->
      <div class="progress-ring text-center">
        <svg width="120" height="120">
          <circle cx="60" cy="60" r="54" stroke="#ffcc00" stroke-width="8" fill="none" opacity="0.2"></circle>
          <circle id="progress-circle" cx="60" cy="60" r="54" stroke="#ff6600" stroke-width="8" fill="none"
            stroke-dasharray="339.29" stroke-dashoffset="339.29" stroke-linecap="round"></circle>
        </svg>
        <div style="position:relative; top:-85px; font-weight:bold;" id="word-count">0 / 150</div>
      </div>
    </div>

  </div>
</section>

 <!-- Footer -->
  <footer class="site-footer text-center">
      <div>Copyright © 2025 Andaz-e-Bayan | All rights reserved</div>
      <div class="footer-links">
        <a href="userCategory.php">Urdu Poetry</a> | <a href="challenges.php">Bait Bazi</a> | 
        <a href="e-commerce.php">Shop</a> | <a href="team.php">Contact</a>
      </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Golden spark animation -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
  const textarea = document.getElementById('entry_text');
  const circle = document.getElementById('progress-circle');
  const wordCount = document.getElementById('word-count');
  const maxWords = 30;
  const radius = 54;
  const circumference = 2 * Math.PI * radius;

  // Ensure SVG stroke values are initialized and animate smoothly
  circle.style.strokeDasharray = `${circumference}`;
  circle.style.strokeDashoffset = `${circumference}`;
  circle.style.transition = 'stroke-dashoffset 350ms ease';

  function updateWordCount() {
    // count words robustly (handles extra spaces/newlines)
    const text = textarea.value.trim();
    const words = text ? text.split(/\s+/).filter(Boolean).length : 0;

    // progress and ring offset
    const progress = Math.min(words / maxWords, 1);
    const offset = circumference - (progress * circumference);
    circle.style.strokeDashoffset = offset;

    // update visible count
    wordCount.textContent = `${words} / ${maxWords}`;

    // optional: change ring color when close to or over the target
    if (words >= maxWords) {
      circle.style.stroke = '#28a745'; // green when complete
    } else if (words >= maxWords * 0.5) {
      circle.style.stroke = '#85fbfdff'; // amber when near
    } else {
      circle.style.stroke = '#ff6600'; // default
    }
  }

  // attach and initialize
  textarea.addEventListener('input', updateWordCount);
  updateWordCount();
});

    const section = document.querySelector('.challenge-section');
    function createSpark() {
      const s = document.createElement('div');
      s.classList.add('spark');
      const size = Math.random() * 6 + 3;
      s.style.width = s.style.height = `${size}px`;
      s.style.left = `${Math.random() * window.innerWidth}px`;
      s.style.top = `${window.innerHeight}px`;
      section.appendChild(s);
      setTimeout(() => s.remove(), 5000);
    }
    setInterval(createSpark, 300);
  </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
