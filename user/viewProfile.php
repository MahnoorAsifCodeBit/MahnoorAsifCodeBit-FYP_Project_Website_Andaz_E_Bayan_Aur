<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../userLogin.php");
    exit();
}

require '../config.php'; // Make sure this has your DB connection

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$query = $conn->prepare("SELECT name, email, contact, profile_pic, bio FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Use default avatar if none exists
if (empty($user['profile_pic'])) {
    $user['profile_pic'] = "avatar.png";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Andaz-e-Bayan Aur</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), url('images/white.png') no-repeat center center/cover;
            font-family: 'Roboto', sans-serif;
            height:820px;
        }
        /* Navbar background color and height adjustments */

        :root {
        --maroon: #800000;
        --maroon-dark: #6c0000;
        --hover-light: #d7ccc8;
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

        .profile-container {
            max-width: 700px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #8B0000;
            margin-bottom: 10px;
        }
        .profile-header h2 {
            font-family: 'Playfair Display', serif;
            color: #8B0000;
            font-weight: 700;
        }
        .profile-form label {
            font-weight: 500;
            color: #333;
        }
        .profile-form input, .profile-form textarea {
            border-radius: 6px;
        }
        .btn-custom {
            background-color: #8B0000;
            color: #fff;
            border: none;
        }
        .btn-custom:hover {
            background-color: #a40000;
            color: white;
        }
        .btn-cancel {
            background-color: #6c757d;
            color: #fff;
        }
        .btn-cancel:hover {
            background-color: #5a6268;
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
        background-color: #750a0a !important;
        }

        .goog-te-gadget-simple .VIpgJd-ZVi9od-xl07Ob-lTBxed {
        color: rgb(255, 255, 255) !important;
        font-size: 16px;
        font-weight: bold;
        }
    </style>
    <script>
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
        <li class="nav-item dropdown">
          <div id="google_translate_element"></div>
        </li>
      </ul>
    </div>
  </div>
</nav>

</div>
<div class="profile-container">
    <div class="profile-header">
        <img src="<?php echo $user['profile_pic']; ?>" id="profilePreview" alt="Profile Picture">
        <h2><?php echo "Welcome ",htmlspecialchars($user['name']); ?></h2>
        <button class="btn btn-custom mt-2" onclick="enableEdit()">Edit Profile</button>
    </div>
    <form class="profile-form" id="profileForm" action="update_profile.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Full Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="form-control" disabled required>
        </div>
        <div class="mb-3">
            <label>Contact Number</label>
            <input type="text" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>" class="form-control" disabled>
        </div>
        <div class="mb-3">
            <label>Email Address</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="form-control" disabled required>
        </div>
        <div class="mb-3">
            <label>Bio</label>
            <textarea name="bio" rows="3" class="form-control" disabled><?php echo $user['bio']; ?></textarea>
        </div>
        <div class="mb-3">
            <label>Change Profile Picture</label>
            <input type="file" name="profile_pic" class="form-control" disabled onchange="previewImage(event)">
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-custom" disabled id="saveBtn">Save Changes</button>
            <button type="button" class="btn btn-cancel" onclick="cancelEdit()" style="display:none;" id="cancelBtn">Cancel</button>
        </div>
    </form>
</div>

<script>
function enableEdit() {
    document.querySelectorAll('#profileForm input, #profileForm textarea').forEach(input => input.disabled = false);
    document.getElementById('saveBtn').disabled = false;
    document.getElementById('cancelBtn').style.display = 'inline-block';
}

function cancelEdit() {
    window.location.reload();
}

function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('profilePreview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
