<?php
include '../config.php';
session_start();




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


// Check if challenge ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Challenge ID is missing.");
}


$challenge_id = (int) $_GET['id']; // Ensure it's an integer

// Fetch challenge details (to get scoring method)
$challenge_query = "SELECT scoring_method FROM challenges WHERE id = $challenge_id";
$challenge_result = mysqli_query($conn, $challenge_query);
$challenge = mysqli_fetch_assoc($challenge_result);

if (!$challenge) {
    die("Error: Challenge not found.");
}

$scoring_method = $challenge['scoring_method']; // Get the scoring type

// Select different scoring mechanisms
switch ($scoring_method) {
    case 'voting':
        $query = "SELECT users.name, challenge_participants.entry_text, COUNT(votes.id) AS score
                  FROM challenge_participants
                  JOIN users ON challenge_participants.user_id = users.id
                  LEFT JOIN votes ON challenge_participants.id = votes.participant_id
                  WHERE challenge_participants.challenge_id = $challenge_id
                  GROUP BY challenge_participants.id
                  ORDER BY score DESC";
        break;
    
    case 'word_count':
        $query = "SELECT users.name, challenge_participants.entry_text, 
                         LENGTH(challenge_participants.entry_text) - LENGTH(REPLACE(challenge_participants.entry_text, ' ', '')) + 1 AS score
                  FROM challenge_participants
                  JOIN users ON challenge_participants.user_id = users.id
                  WHERE challenge_participants.challenge_id = $challenge_id
                  ORDER BY score DESC";
        break;

    case 'engagement':
        $query = "SELECT users.name, challenge_participants.entry_text, 
                         (COALESCE(SUM(likes.count), 0) + COALESCE(SUM(comments.count), 0)) AS score
                  FROM challenge_participants
                  JOIN users ON challenge_participants.user_id = users.id
                  LEFT JOIN likes ON challenge_participants.id = likes.participant_id
                  LEFT JOIN comments ON challenge_participants.id = comments.participant_id
                  WHERE challenge_participants.challenge_id = $challenge_id
                  GROUP BY challenge_participants.id
                  ORDER BY score DESC";
        break;

    default: // Admin-assigned scoring (default)
        $query = "SELECT users.name, challenge_participants.entry_text, challenge_participants.score
                  FROM challenge_participants
                  JOIN users ON challenge_participants.user_id = users.id
                  WHERE challenge_participants.challenge_id = $challenge_id
                  ORDER BY challenge_participants.score DESC";
        break;
}

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Leaderboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Gulzar&display=swap" rel="stylesheet">
    <style>
        /* Global styles */
        html, body {
            height: 100%;
            margin: 0;
          }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            /* background: linear-gradient(to bottom,rgb(73, 0, 0),rgb(88, 54, 44)); */
            background-attachment: fixed;
            color: white;
            text-align: center;
            margin: 0px;
            padding: 0px;
        }


        /* Leaderboard Title */
        h1 {
            font-size: 42px;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 20px;
            background: linear-gradient(90deg, #491919ff, #9e1414ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-top: 3%;
            color: #9e1414ff;
        }

        /* Scoring method label */
        p {
            font-size: 18px;
            font-weight: 500;
            color: #462929ff;
        }

        /* Leaderboard Container */
        .leaderboard {
            width: 80%;
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        /* Leaderboard Entry - Card Style */
        .leaderboard-entry {
            width: 90%;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1); /* Frosted Glass */
            border-radius: 16px;
            backdrop-filter: blur(15px);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Hover effect */
        .leaderboard-entry:hover {
            transform: translateY(-5px);
            box-shadow: 0px 10px 25px rgba(255, 255, 255, 0.2);
        }

        /* Rank Styling */
        .rank {
            display: flex;
            align-items: center;
            gap: 10px; /* adds nice space between icon and text */
            font-size: 28px;
            font-weight: bold;
            min-width: 60px;
            justify-content: center;
        }


        /* User Name */
        .user-name {
            font-size: 20px;
            font-weight: bold;
            color: #4b0e19;
            text-shadow: 0px 0px 5px rgba(255, 255, 255, 0.2);
        }

        /* Poetry Content */
        .poetry {
            font-family: Gulzar, serif;
            flex-grow: 1;
            font-size: 20px;
            color: rgba(66, 29, 29, 1);
            padding: 0 15px;
        }

        /* Score */
        .score {
            font-size: 22px;
            font-weight: bold;
            color: #4c2825e8;
        }

        /* Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .leaderboard-entry {
                flex-direction: column;
                text-align: center;
            }
            .poetry {
                padding: 10px 0;
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
.container {
  flex: 1; /* pushes footer down */
}

.site-footer {
  background: #4b0e19;
  color: #fff;
  color: #fff;
  padding: 15px 0;
  font-size: 14px;
  position: relative;
  bottom: 0;
  width: 100%;
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

<h1><i class="fas fa-crown"></i> &nbsp; Leaderboard</h1>
<!-- <p>Scoring Method: <strong><?= ucfirst($scoring_method); ?></strong></p> -->

<div class="leaderboard">
<?php
// make sure $result was created earlier (your code does this). Safe-check:
if ($result && mysqli_num_rows($result) > 0) {
    $rank = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        // rank class (for styling)
        $rankClass = ($rank == 1) ? 'gold' : (($rank == 2) ? 'silver' : (($rank == 3) ? 'bronze' : ''));

        // choose icon (Bootstrap Icons)
        if ($rank == 1) {
            $icon = '<i class="bi bi-trophy-fill" style="color: #FFD700;"></i>'; // gold
        } elseif ($rank == 2) {
            $icon = '<i class="bi bi-trophy-fill" style="color: #C0C0C0;"></i>'; // silver
        } elseif ($rank == 3) {
            $icon = '<i class="bi bi-trophy-fill" style="color: #CD7F32;"></i>'; // bronze
        } else {
            $icon = '<i class="bi bi-trophy" style="color: #ffffff55;"></i>'; // neutral for others
        }

        // safe fallbacks in case some columns are missing
        $name = isset($row['name']) ? htmlspecialchars($row['name']) : 'Unknown';
        $entry_text = isset($row['entry_text']) ? nl2br(htmlspecialchars($row['entry_text'])) : '';
        $score = isset($row['score']) ? $row['score'] : '0';
        ?>
        <div class="leaderboard-entry">
            <span class="rank <?= $rankClass ?>"><?= $icon ?>&nbsp;<?= $rank ?></span>
            <span class="user-name"><?= $name ?></span>
            <span class="poetry"><?= $entry_text ?></span>
            <span class="score"><?= $score ?></span>
        </div>
        <?php
        $rank++;
    } // end while
} else {
    // no rows or query failed
    echo '<p class="text-muted">No participants found yet.</p>';
}
?>

</div>
     <!-- Footer -->
  <footer class="site-footer text-center">
      <div>Copyright © 2025 Andaz-e-Bayan | All rights reserved</div>
      <div class="footer-links">
        <a href="userCategory.php">Urdu Poetry</a> | <a href="challenges.php">Bait Bazi</a> | 
        <a href="e-commerce.php">Shop</a> | <a href="team.php">Contact</a>
      </div>
  </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
