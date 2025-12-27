<?php
include '../config.php'; 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../userlogin.php");
    exit();
}

// Fetch all challenges
$query = "SELECT * FROM challenges ORDER BY start_date ASC";
$result = mysqli_query($conn, $query);

// Current time
$currentTime = date("Y-m-d H:i:s");

// Default user info
$user_name = "Guest";
$profile_pic = 'images/nodp.png';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT name, profile_pic FROM users WHERE id = '$user_id'";
    $user_result = mysqli_query($conn, $query);

    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user = mysqli_fetch_assoc($user_result);
        $user_name = $user['name'];

        if (!empty($user['profile_pic'])) {
            if (str_starts_with($user['profile_pic'], 'uploads/')) {
                $profile_pic = '/andazebayan-admin/user/' . $user['profile_pic']; 
            } else {
                $profile_pic = '/andazebayan-admin/user/images/' . $user['profile_pic'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bait Baazi Challenges</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Bootstrap / Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --maroon: #800000;
      --maroon-dark: #5c0000;
      --light: #ffffff;
      --accent: #FFD700;
    }

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      /* background: linear-gradient(135deg, var(--maroon-dark), var(--maroon)); */
      color: var(--light);
      min-height: 100vh;
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

    /* Heading */
    h2.heading {
      text-align: center;
      margin: 40px 0 20px;
      font-size: 2.2rem;
      font-weight: 700;
      color: maroon;
      letter-spacing: 1px;

    }

    /* Challenge container */
    .challenges-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      padding: 40px 60px;
    }

    /* Challenge Card */
    .challenge-card {
      background: rgba(255,255,255,0.08);
      border-radius: 18px;
      padding: 25px;
      border: 1px solid rgba(255,255,255,0.15);
      backdrop-filter: blur(12px);
      box-shadow: 0px 8px 20px rgba(0,0,0,0.3);
      transition: all 0.3s ease;
    }
    .challenge-card:hover {
      transform: translateY(-6px);
      border-color: rgba(255,255,255,0.4);
      box-shadow: 0px 12px 25px rgba(0,0,0,0.4);
    }
    .challenge-card h3 {
      font-size: 1.4rem;
      margin-bottom: 10px;
      font-weight: 600;
      color: maroon;
    }
    .challenge-card p {
      margin: 5px 0;
      font-size: 0.95rem;
      color: maroon;
    }

    /* Status labels */
    .status {
      display: inline-block;
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      margin-bottom: 12px;
    }
    .status.active { background: #28a745; color: #fff; }
    .status.upcoming { background: #ffc107; color: #000; }
    .status.expired { background: #dc3545; color: #fff; }

    /* Buttons */
    .btn-custom {
      display: block;
      width: 100%;
      margin-top: 12px;
      padding: 12px;
      border-radius: 25px;
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s;
      text-align: center;
    }
    .join-btn {
      background: linear-gradient(90deg, #FFD700, #FF8C00);
      color: #000;
    }
    .join-btn:hover { transform: scale(1.05); }
    .join-btn.disabled {
      background: gray;
      cursor: not-allowed;
      opacity: 0.6;
    }
    .leaderboard-btn {
      background: #4b0e19;
      border: 1px solid rgba(255,255,255,0.3);
      color: #fff;
    }
    .leaderboard-btn:hover {
      background: rgba(153, 4, 4, 0.71);
      transform: scale(1.05);
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

  <!-- Google Translate -->
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

<h2 class="heading"><i class="fas fa-medal"></i> Bait Baazi Challenges</h2>

<div class="challenges-container">
  <?php while ($row = mysqli_fetch_assoc($result)) { 
    if ($currentTime >= $row['start_date'] && $currentTime <= $row['end_date']) {
        $status = 'active';
    } elseif ($currentTime < $row['start_date']) {
        $status = 'upcoming';
    } else {
        $status = 'expired';
    }
    $statusClass = ($status == 'active') ? 'active' : (($status == 'upcoming') ? 'upcoming' : 'expired');
  ?>
    <div class="challenge-card">
      <h3><?= $row['title']; ?></h3>
      <span class="status <?= $statusClass; ?>"><?= ucfirst($status); ?></span>
      <p><strong>Category:</strong> <?= $row['category']; ?></p>
      <p><strong>Starts:</strong> <?= date("M d, Y H:i", strtotime($row['start_date'])); ?></p>
      <p><strong>Ends:</strong> <?= date("M d, Y H:i", strtotime($row['end_date'])); ?></p>

      <a href="join_challenge.php?id=<?= $row['id']; ?>" 
         class="btn-custom join-btn <?= ($status !== 'active') ? 'disabled' : ''; ?>" 
         <?= ($status !== 'active') ? 'onclick="return false;"' : ''; ?>>
         <?= ($status == 'active') ? 'Join Challenge' : 'Not Available'; ?>
      </a>

      <a href="leadership.php?id=<?= $row['id']; ?>" class="btn-custom leaderboard-btn">View Leaderboard</a>
    </div>
  <?php } ?>
</div>

 <!-- Footer -->
  <footer class="site-footer">
    <div class="container text-center">
      <div>Copyright © 2025 Andaz-e-Bayan | All rights reserved</div>
      <div class="footer-links">
        <a href="#">Urdu Poetry</a> | <a href="#">Bait Bazi</a> | <a href="#">Shop</a> | <a href="#">Contact</a>
      </div>
    </div>
  </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
