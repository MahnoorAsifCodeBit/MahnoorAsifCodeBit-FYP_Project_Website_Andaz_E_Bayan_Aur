<?php
session_start();
 include("../config.php");
// team.php
$team_members = [
    [
        'name' => 'Momina Sheikh',
        'role_ab' => 'Product Owner',
        'role_prof' => 'Microsoft Power Platform Consultant at 1Dynamics',
        'bio' => 'Leading vision, strategy, and product direction of Andaz-e-Bayan Aur.',
        'image' => 'images/momina.png',
        'linkedin' => 'https://linkedin.com/in/momina-sheikh-24660926b',
        'instagram' => '#',
        'icon' => 'fa-crown'
    ],
    [
        'name' => 'Umm-e-Hani Baig',
        'role_ab' => 'Solution Architect',
        'role_prof' => 'Microsoft Power Platform Consultant at 1Dynamics',
        'bio' => 'Ensuring scalable system design and solution architecture.',
        'image' => 'images/hani.jpg',
        'linkedin' => '#',
        'instagram' => '#',
        'icon' => 'fa-drafting-compass'
    ],
    [
        'name' => 'Mahnoor Asif',
        'role_ab' => 'Scrum Master',
        'role_prof' => 'Developer Intern at 10Pearls',
        'bio' => 'Managing sprints and agile delivery for Andaz-e-Bayan Aur.',
        'image' => 'images/mahnoor.jpg',
        'linkedin' => '#',
        'instagram' => '#',
        'icon' => 'fa-tasks'
    ],
    [
        'name' => 'Aiman Yaqoob',
        'role_ab' => 'Tester',
        'role_prof' => 'Data Science Intern at 10Pearls',
        'bio' => 'Responsible for testing and quality assurance.',
        'image' => 'images/aiman.jpg',
        'linkedin' => '#',
        'instagram' => '#',
        'icon' => 'fa-flask'
    ],
];

$supervisors = [
    [
        'name' => 'Prof. Dr. Narmeen Zakariya Bawany',
        'role_ab' => 'Supervisor & Founder (CEO) ',
        'role_prof' => 'Dean of Faculty of Science, JUW',
        'bio' => 'Providing academic supervision and guidance.',
        'image' => 'images/narmeen.jpeg',
        'linkedin' => '#',
        'instagram' => '#',
        'icon' => 'fa-graduation-cap'
    ],
    [
        'name' => 'Ms. Kanwal Zahoor',
        'role_ab' => 'Co-Supervisor & Co-Founder',
        'role_prof' => 'Lecturer at JUW',
        'bio' => 'Supporting with mentorship and academic insights.',
        'image' => 'images/kanwal.jpeg',
        'linkedin' => '#',
        'instagram' => '#',
        'icon' => 'fa-graduation-cap'
    ],
];

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
        // This was the missing part:
        $profile_pic = 'default.png'; // Assuming you want a fallback icon here

    }
} else {
    $user_name = "Guest";
    $profile_pic = 'images/nodp.png';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Meet the Team | Andaz-e-Bayan Aur</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="land.css"> -->
    
    <script type="text/javascript">
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({
      pageLanguage: 'en',      
      includedLanguages: 'en,ur,ar,hi,fr,id,ja',
      layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
  }</script>
  <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
  <style>
    body { font-family: 'Poppins', sans-serif; background:#fdfaf8; color:#333; }
    .hero { background: linear-gradient(180deg,#6c0d1e,#4b0e19); color:#fff; padding:60px 0; text-align:center; margin-bottom:40px; }
    .section-title { font-size:1.8rem; font-weight:600; text-align:center; margin:40px 0 20px; color:#6c0d1e; }

    .team-grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(260px,1fr)); gap:25px; max-width:1200px; margin:auto; }

    .flip-card { background:transparent; perspective:1000px; position:relative; }
    .flip-card-inner { position:relative; width:100%; height:380px; text-align:center; transition: transform 0.8s; transform-style: preserve-3d; }
    .flip-card-inner.flipped { transform: rotateY(180deg); }
    .flip-card-front, .flip-card-back {
      position:absolute; width:100%; height:100%; backface-visibility:hidden;
      border-radius:15px; box-shadow:0 8px 25px rgba(0,0,0,0.1); overflow:hidden; padding:20px;
    }
    .flip-card-front { background:#fff; display:flex; flex-direction:column; justify-content:center; align-items:center; }
    .flip-card-front img { width:100px; height:100px; border-radius:50%; object-fit:cover; margin-bottom:15px; }
    .flip-card-front h3 { font-size:1.1rem; font-weight:600; margin-bottom:5px; }
    .flip-card-front p { font-size:0.9rem; color:#666; }
    .socials a { color:#6c0d1e; font-size:1.2rem; margin:0 8px; transition:0.3s; }
    .socials a:hover { color:#000; }

    .flip-card-back { background:#6c0d1e; color:#fff; transform:rotateY(180deg); display:flex; flex-direction:column; justify-content:center; align-items:center; padding:25px; }
    .flip-card-back h4 { font-size:1rem; margin-bottom:10px; }
    .flip-card-back p { font-size:0.9rem; }

    /* Flip button */
    .flip-trigger {
      position:absolute; bottom:10px; right:10px;
      background:#6c0d1e; color:#fff; border-radius:50%; width:35px; height:35px;
      display:flex; justify-content:center; align-items:center;
      cursor:pointer; transition:0.3s; box-shadow:0 2px 6px rgba(0,0,0,0.2);
    }
    .flip-trigger:hover { background:#4b0e19; }
    
    /* Search Bar */
    .search-box { max-width:500px; margin:20px auto; position:relative; }
    .search-box input { padding:10px 40px; border-radius:30px; border:1px solid #ccc; width:100%; }
    .search-box i { position:absolute; top:50%; left:15px; transform:translateY(-50%); color:#999; }
        /* Navbar */
      
@media (max-width: 768px) {
  .nav-home a {
    font-size: 18px;
    margin: 10px;
  }
}
@media (max-width: 576px) {
  .carousel-title {
    font-size: 2rem;
  }
  .carousel-caption-text {
    font-size: 1rem;
  }
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

.navbar {
  position: relative; /* Sticks to the top */
  top: 0;
  left: 0;
  width: 99vw; /* Full width */
  height: 70px;
  z-index: 99999; /* Stays on top of other content */
  background: #6c0d1e; /* Semi-transparent maroon */
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
  background-color: #800000;
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
  border: 1px solid rgb(108, 0, 0);
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
  background-color: #6c0d1e !important;
  border: none !important;
  color: white !important;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
}
.goog-te-gadget-simple:hover {
  background-color: #6c0d1e !important;
}

.goog-te-gadget-simple .VIpgJd-ZVi9od-xl07Ob-lTBxed {
  color: rgb(255, 255, 255) !important;
  font-size: 16px;
  font-weight: bold;
}
    /* Footer */
.site-footer {
  background: #6c0000;
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
         <li class="nav-item"><a class="nav-link" href="team.php"><i class="fas fa-people-arrows"></i> Team </a></li>
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

  <!-- HERO -->
  <section class="hero">
    <div class="container">
      <h1>Meet the Team</h1>
      <p>الفاظ کے پیچھے چہرے → The creatives, mentors and visionaries behind <strong>Andaz-e-Bayan Aur</strong>.</p>
    </div>
  </section>

  <!-- Search -->
  <div class="search-box">
    <i class="fas fa-search"></i>
    <input type="text" id="searchInput" placeholder="Search team members...">
  </div>

  <!-- Supervisors -->
  <h2 class="section-title">Supervisors</h2>
  <div class="team-grid" id="supervisors">
    <?php foreach($supervisors as $s): ?>
    <div class="flip-card team-member">
      <div class="flip-card-inner">
        <!-- FRONT -->
        <div class="flip-card-front">
          <img src="<?=htmlspecialchars($s['image']);?>" onerror="this.src='https://via.placeholder.com/150'">
          <h3><?=htmlspecialchars($s['name']);?></h3>
          <p><i class="fas <?=$s['icon'];?>"></i> <?=htmlspecialchars($s['role_ab']);?></p>
          <div class="socials">
            <a href="<?=$s['linkedin'];?>"><i class="fab fa-linkedin"></i></a>
            <a href="<?=$s['instagram'];?>"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
        <!-- BACK -->
        <div class="flip-card-back">
          <h4><?=htmlspecialchars($s['role_prof']);?></h4>
          <p><?=htmlspecialchars($s['bio']);?></p>
        </div>
        <!-- Flip Button -->
        <div class="flip-trigger"><i class="fas fa-sync-alt"></i></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Core Team -->
  <h2 class="section-title">Core Team</h2>
  <div class="team-grid" id="coreTeam">
    <?php foreach($team_members as $m): ?>
    <div class="flip-card team-member">
      <div class="flip-card-inner">
        <!-- FRONT -->
        <div class="flip-card-front">
          <img src="<?=htmlspecialchars($m['image']);?>" onerror="this.src='https://via.placeholder.com/150'">
          <h3><?=htmlspecialchars($m['name']);?></h3>
          <p><i class="fas <?=$m['icon'];?>"></i> <?=htmlspecialchars($m['role_ab']);?></p>
          <div class="socials">
            <a href="<?=$m['linkedin'];?>"><i class="fab fa-linkedin"></i></a>
            <a href="<?=$m['instagram'];?>"><i class="fab fa-instagram"></i></a>
          </div>
        </div>
        <!-- BACK -->
        <div class="flip-card-back">
          <h4><?=htmlspecialchars($m['role_prof']);?></h4>
          <p><?=htmlspecialchars($m['bio']);?></p>
        </div>
        <!-- Flip Button -->
        <div class="flip-trigger"><i class="fas fa-sync-alt"></i></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <script>
    // Filter team members
    document.getElementById("searchInput").addEventListener("keyup", function() {
      let filter = this.value.toLowerCase();
      document.querySelectorAll(".team-member").forEach(function(card) {
        let text = card.innerText.toLowerCase();
        card.style.display = text.includes(filter) ? "" : "none";
      });
    });

    // Flip logic
    document.querySelectorAll(".flip-trigger").forEach(btn => {
      btn.addEventListener("click", function(e) {
        let inner = e.currentTarget.closest(".flip-card-inner");
        inner.classList.toggle("flipped");
      });
    });
  </script>
  <br><br>
   <!-- Footer -->
  <footer class="site-footer">
    <div class="container text-center">
      <div>Copyright © 2025 Andaz-e-Bayan | All rights reserved</div>
      <div class="footer-links">
        <a href="#">Urdu Poetry</a> | <a href="#">Bait Bazi</a> | <a href="#">Shop</a> | <a href="#">Contact</a>
      </div>
    </div>
  </footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
