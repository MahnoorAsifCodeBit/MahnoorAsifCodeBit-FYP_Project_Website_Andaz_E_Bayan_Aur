<?php 
session_start();
include '../config.php'; // Database connection


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_poetry'])) {
    $content = trim($_POST['content']);
    if (!empty($content)) {
        $sql = "INSERT INTO poetry_library (poetry_text) VALUES (?)";
        $stmt = $conn->prepare($sql);


        if (!$stmt) {
            die("SQL error: " . $conn->error);
        }


        $stmt->bind_param("s", $content);


        if ($stmt->execute()) {
            $_SESSION['message'] = "✔ Poetry added successfully!";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['message'] = "✘ Failed to add poetry.";
        }


        $stmt->close();
    } else {
        $_SESSION['message'] = "⚠ Poetry content cannot be empty.";
    }
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
        // This was the missing part:
        $profile_pic = 'default.png'; // Assuming you want a fallback icon here


    }
} else {
    $user_name = "Guest";
    $profile_pic = 'images/nodp.png';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_poetry'])) {
    $delete_id = intval($_POST['delete_id']);
    $sql = "DELETE FROM poetry_library WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "🗑 Poetry deleted successfully!";
    } else {
        $_SESSION['message'] = "✘ Failed to delete poetry.";
    }
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Design Studio - Custom Calligraphy</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&family=Reem+Kufi&family=Amiri&family=Lateef&family=Tajawal&family=Scheherazade&family=Sakkal+Majalla&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="land.css">
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

/* Reset all unwanted spacing */
html, body {
  margin: 0;
  padding: 0;
  width: 100%;
  height: auto;            /* allow full scroll */
  min-height: 100vh;
  overflow-x: hidden;      /* no side scroll */
  overflow-y: auto;        /* vertical scroll enabled */
  background: #fff;
}


/* Force main container to stretch full screen width */
.main-container {
  display: flex;
  min-height: calc(100vh - 70px);  /* takes full height but allows scroll if more content */
  width: 100%;
  margin: 0;
  padding: 0;
  position: relative;              /* ✅ no longer fixed */
}



/* Right Image Panel */
.image-panel {
  width: 320px;
  background: rgba(255, 255, 255, 0.9);
  border-left: 2px solid #800000;
  padding: 20px 15px;
  overflow-y: auto;
  box-shadow: -4px 0 12px rgba(0,0,0,0.1);
}


.right-panel {
  width: 340px;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border-right: 3px solid #800000;
  padding: 20px 16px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  position: relative;
  height: 100vh;
  box-shadow: 4px 0 12px rgba(0, 0, 0, 0.1);
  margin: 0;              /* removes unwanted space */
}



/* Reset & base */
  * {
    box-sizing: border-box;
  }
  body {
    font-family: 'Tajawal', sans-serif;
    margin: 0;
    background: #f4f4f9;
    color: #333;
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



  h2, h3, h4 {
    font-family: 'Noto Nastaliq Urdu', serif;
    color: #800000;
  }
  .container {
    display: flex;
    height: 100vh;
    overflow: hidden;
  }
  .right-panel {
    width: 320px;
    background: #fff;
    border-right: 2px solid #800000;
    padding: 20px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
  }
  /* Message alert */
  .alert {
    background-color: #d4edda;
    border-left: 5px solid #28a745;
    padding: 10px 15px;
    margin-bottom: 15px;
    font-weight: 600;
    border-radius: 3px;
  }
  .alert.error {
    background-color: #f8d7da;
    border-left-color: #dc3545;
    color: #721c24;
  }
  form textarea {
    width: 100%;
    resize: vertical;
    min-height: 80px;
    font-family: 'Noto Nastaliq Urdu', serif;
    font-size: 16px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
  }
  form button {
    background-color: #800000;
    color: white;
    padding: 10px 15px;
    border: none;
    cursor: pointer;
    border-radius: 6px;
    font-size: 16px;
    transition: background-color 0.3s ease;
  }
  form button:hover {
    background-color: #a33a3a;
  }
  label {
    font-weight: 700;
    margin-top: 15px;
    display: block;
  }
  select, input[type="color"] {
    margin-top: 6px;
    width: 100%;
    padding: 6px;
    border-radius: 5px;
    border: 1px solid #ccc;
    cursor: pointer;
  }
  .poetry-list {
    margin-top: 15px;
    flex-grow: 1;
    overflow-y: auto;
  }
  .poetry-card {
    background: transparent;
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 8px;
    box-shadow: 1px 1px 6px rgba(0,0,0,0.07);
    cursor: pointer;
    font-family: 'Noto Nastaliq Urdu', serif;
    font-size: 16px;
    transition: background-color 0.25s ease;
    user-select: none;
  }
  
  /* Styling buttons for text style toggles and canvas controls */
  .controls {
  margin-top: 15px;
  display: flex;            /* always visible */
  flex-wrap: wrap;
  gap: 8px;
  align-items: center;
  justify-content: flex-start;
}
.controls button {
  flex: 0 0 auto;
  min-width: 64px;
}



  .controls button {
    flex: 1 1 45%;
    padding: 8px;
    font-weight: 600;
    border: 2px solid #800000;
    background: white;
    color: #800000;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.25s ease, color 0.25s ease;
  }
  .controls button:hover {
    background-color: #800000;
    color: white;
  }
  /* Canvas area styling */
  .canvas-area {
    flex-grow: 1;
    background: #fff;
    position: relative;
    display: flex;
    flex-direction: column;
    box-shadow: inset 0 0 30px #ddd;
  }
  .canvas-header {
    padding: 15px;
    border-bottom: 2px solid #800000;
    background: #f9f9f9;
    text-align: center;
  }
  #designCanvas {
    flex-grow: 1;
    position: relative;
    background-color: #fefefe;
    overflow: hidden;
  }
.canvas-item {
  position: absolute;
  display: inline-block;    /* shrink-wrap to text */
  width: auto;              /* no fixed width */
  height: auto;             /* adapt to text */
  white-space: pre;         /* keep line breaks & spacing */
  padding: 4px 6px;         /* optional breathing space */
  border-radius: 6px;
  background: transparent;
  cursor: move;
  user-select: none;
}



.canvas-item:hover {
  box-shadow: 0 4px 5px rgba(0,0,0,0.3);
}


/* Only show dashed outline when actively selected */
.canvas-item.selected {
  outline: 2px dashed #800000;
  position: relative;
}


  .canvas-item.selected {
    outline: 2px dashed #800000;
    position: relative;
    box-shadow: 0 6px 12px rgba(128,0,0,0.08);
  }


/* Handles hidden by default, added dynamically on selection */
.resize-handle,
.rotate-handle {
  display: none;
}


.canvas-item.selected .resize-handle,
.canvas-item.selected .rotate-handle {
  display: block;
  position: absolute;
  width: 14px;
  height: 14px;
  background: #800000;
  border-radius: 50%;
}


.resize-handle {
  bottom: -8px;
  right: -8px;
  cursor: nwse-resize;
}


.rotate-handle {
  top: -20px;
  left: 50%;
  transform: translateX(-50%);
  cursor: grab;
}
/* Scrollbar for poetry list */
  .poetry-list::-webkit-scrollbar {
    width: 8px;
  }
  .poetry-list::-webkit-scrollbar-thumb {
    background-color: #800000cc;
    border-radius: 4px;
  }
  .poetry-list::-webkit-scrollbar-track {
    background: transparent;
  }
  /* Responsive tweaks */
  @media (max-width: 900px) {
    .container {
      flex-direction: column;
      height: auto;
    }
    .right-panel {
      width: 100%;
      height: auto;
      border-right: none;
      border-bottom: 2px solid #800000;
    }
    .canvas-area {
      height: 400px;
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


.poetry-list {
  position: relative;
  min-height: 300px; /* keeps layout stable even when empty */
  display: flex;
  flex-direction: column;
  gap: 8px;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background: #fff;
}

.poetry-heading {
  font-size: 1rem;
  text-align: center;
  font-weight: 600;
  color: #800000;
  margin-bottom: 8px;
}

.no-poetry {
  text-align: center;
  color: #888;
  font-style: italic;
}


.poetry-card {
  background: #fff;
  border: 1px solid #ddd;
  padding: 8px 12px;
  margin-bottom: 10px;
  border-radius: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: all 0.2s ease-in-out;
}

.poetry-card:hover {
  background: #fcebea;
  box-shadow: 0 2px 5px rgba(128, 0, 0, 0.1);
}

.poetry-card .poetry-text {
  flex: 1;
  font-family: 'Noto Nastaliq Urdu', serif;
  font-size: 16px;
  margin-right: 10px;
}

.delete-form {
  margin: 0;
}

.delete-btn {
  background: transparent;
  border: none;
  color: #a00;
  font-size: 18px;
  cursor: pointer;
  transition: transform 0.2s ease, color 0.3s;
}

.delete-btn:hover {
  color: #800000;
  transform: scale(1.2);
}



.image-panel {
  width: 320px;
  background: rgba(255, 255, 255, 0.9);
  border-left: 2px solid #800000;
  padding: 20px 15px;
  overflow-y: auto;
  box-shadow: -4px 0 12px rgba(0,0,0,0.1);
  display: flex;
  flex-direction: column;
}

.image-heading {
  font-family: 'Noto Nastaliq Urdu', serif;
  font-size: 1.2rem;
  font-weight: 600;
  color: #800000;
  text-align: center;
  margin-bottom: 15px;
}

.image-gallery {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.image-card {
  position: relative;
  border: 1px solid #ddd;
  border-radius: 10px;
  overflow: hidden;
  background: #fff;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.image-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 10px rgba(128,0,0,0.2);
}

.image-card img {
  width: 100%;
  height: auto;
  display: block;
}

.download-btn {
  display: block;
  text-align: center;
  background: #800000;
  color: white;
  text-decoration: none;
  font-weight: 600;
  padding: 8px 0;
  transition: background 0.3s ease;
}

.download-btn:hover {
  background: #a33a3a;
}




</style>
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


<div class="main-container">


  <div class="right-panel">
    <?php if (isset($_SESSION['message'])): ?>
      <div class="alert <?= strpos($_SESSION['message'], '✘') !== false ? 'error' : '' ?>">
        <?= htmlspecialchars($_SESSION['message']) ?>
      </div>
    <?php unset($_SESSION['message']); endif; ?>


    <div>
      <h3>Add New Poetry</h3>
      <form method="POST" action="customization.php" novalidate>
        <textarea name="content" placeholder="Write your poetry here..." required></textarea>
        <br>
        <button type="submit" name="add_poetry">Save Poetry</button>
      </form>
    </div>

    
<div class="poetry-list" id="poetryList">
  <h4 class="poetry-heading">Click to Add on Canvas</h4>
  <?php
  $result = $conn->query("SELECT * FROM poetry_library ORDER BY id DESC");
  if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $poetry_safe_js = json_encode($row['poetry_text'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
          $poetry_display = htmlspecialchars($row['poetry_text']);
          echo "
          <div class='poetry-card' data-poetry='$poetry_safe_js'>
            <div class='poetry-text'>$poetry_display</div>
            <form method='POST' class='delete-form'>
              <input type='hidden' name='delete_id' value='{$row['id']}'>
              <button type='submit' name='delete_poetry' class='delete-btn'><i class='bi bi-trash'></i></button>
            </form>
          </div>";
      }
  } else {
      echo "<p class='no-poetry'>No poetry found.</p>";
  }
  ?>
</div>




    <div>
      <label for="fontSelector">Select Font</label>
      <select id="fontSelector" onchange="changeFontStyle(this.value)">
        <option value="'Noto Nastaliq Urdu', serif" selected>Nastaliq Urdu</option>
        <option value="'Reem Kufi', sans-serif">Reem Kufi</option>
        <option value="'Tajawal', sans-serif">Tajawal</option>
        <option value="'Scheherazade', serif">Scheherazade</option>
        <option value="'Sakkal Majalla', serif">Sakkal Majalla</option>
      </select>


      <label for="fontColor">Select Text Color</label>
      <input type="color" id="fontColor" value="#000000" onchange="changeFontColor(this.value)" />


      <label for="canvasColor">Select Canvas Background Color</label>
      <input type="color" id="canvasColor" value="#fefefe" onchange="changeCanvasColor(this.value)" />
    </div>




    <div class="controls" aria-label="Text styling controls">
      <button type="button" onclick="toggleBold()" title="Toggle Bold (Select text)">Bold</button>
      <button type="button" onclick="toggleItalic()" title="Toggle Italic">Italic</button>
      <button type="button" onclick="toggleUnderline()" title="Toggle Underline">Underline</button>
      <button type="button" onclick="toggleTextShadow()" title="Toggle Text Shadow">Text Shadow</button>
      <button type="button" onclick="resizeText('increase')" title="Increase Font Size">A+</button>
      <button type="button" onclick="resizeText('decrease')" title="Decrease Font Size">A-</button>
      <button type="button" onclick="clearCanvas()" title="Clear Canvas">Clear Canvas</button>
      <button id="downloadTemplateBtn" style="margin-top:10px; padding:10px 15px; background:#800000; color:white; border:none; border-radius:6px; cursor:pointer;">
  Download Template
</button>

    </div>


  </div>


  <div class="canvas-area" role="main" aria-label="Design canvas area">
    <div class="canvas-header">
      <h2>Design Your Custom Calligraphy Frame</h2>
      <p style="font-style: italic; color: #555;">Select poetry from the left, drag it here, and style it beautifully.</p>
    </div>
    <div id="designCanvas" tabindex="0" aria-live="polite" aria-label="Canvas design area"></div>
  </div>

  <!-- RIGHT IMAGE PANEL -->
<div class="image-panel">
  <h4 class="image-heading">Poetry Inspiration</h4>
  <div class="image-gallery">
    <?php
      // Example static images (you can later make dynamic)
      $images = [
        'images/cust1.png',
        'images/poetry2.jpg',
        'images/poetry3.jpg',
        'images/poetry4.jpg'
      ];
      foreach ($images as $img) {
          echo "
          <div class='image-card'>
            <img src='$img' alt='Poetry Image'>
            <a href='$img' download class='download-btn'>
              <i class='bi bi-download'></i> Save
            </a>
          </div>
          ";
      }
    ?>
  </div>
</div>



</div>

<script>
'use strict';

const canvas = document.getElementById('designCanvas');
const poetryList = document.getElementById('poetryList');
const fontSelector = document.getElementById('fontSelector');
const fontColorInput = document.getElementById('fontColor');
let pendingSelectionItem = null;

let currentCanvasItem = null;

const defaults = {
  fontFamily: fontSelector ? fontSelector.value : "'Noto Nastaliq Urdu', serif",
  color: fontColorInput ? fontColorInput.value : '#000000',
  fontSize: 24,
  fontWeight: 'normal',
  fontStyle: 'normal',
  textDecoration: 'none',
  textShadow: ''
};

function addToCanvas(text) {
  if (!text) return;
  const div = document.createElement('div');
  div.className = 'canvas-item';
  div.innerText = text;
  div.style.position = 'absolute';
  div.style.left = '100px';
  div.style.top = '100px';
  div.style.whiteSpace = 'pre';
  div.style.fontFamily = defaults.fontFamily;
  div.style.fontSize = defaults.fontSize + 'px';
  div.style.color = defaults.color;
  div.style.fontWeight = defaults.fontWeight;
  div.style.fontStyle = defaults.fontStyle;
  div.style.textDecoration = defaults.textDecoration;
  if (defaults.textShadow) div.style.textShadow = defaults.textShadow;
  div.dataset.rotation = '0';
  div.tabIndex = 0;

div.addEventListener('mousedown', (e) => {
  e.stopPropagation();
  pendingSelectionItem = div; // immediately update pending selection
  selectCanvasItem(div); // updates global and UI
});


  div.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      selectCanvasItem(div);
    }
  });

  canvas.appendChild(div);
  makeDraggable(div, canvas);
  selectCanvasItem(div);
}

function selectCanvasItem(item) {
  document.querySelectorAll('.canvas-item.selected').forEach(el => {
    el.classList.remove('selected');
    removeHandles(el);
  });

  currentCanvasItem = item;
  if (!item) return;

  item.classList.add('selected');
  removeHandles(item);

  const resize = document.createElement('div');
  resize.className = 'resize-handle';
  const rotate = document.createElement('div');
  rotate.className = 'rotate-handle';
  item.appendChild(resize);
  item.appendChild(rotate);

  enableResize(resize, item);
  enableRotate(rotate, item);

  if (fontSelector) {
    const fam = item.style.fontFamily || defaults.fontFamily;
    const opt = Array.from(fontSelector.options).find(o => o.value === fam);
    if (opt) fontSelector.value = fam;
  }
  if (fontColorInput) {
    fontColorInput.value = rgbToHex(window.getComputedStyle(item).color || item.style.color || defaults.color);
  }
}

function deselectAll() {
  document.querySelectorAll('.canvas-item.selected').forEach(el => {
    el.classList.remove('selected');
    removeHandles(el);
  });
  currentCanvasItem = null;
}

function removeHandles(el) {
  if (!el) return;
  const r = el.querySelector('.resize-handle');
  const ro = el.querySelector('.rotate-handle');
  if (r) r.remove();
  if (ro) ro.remove();
}

function rgbToHex(rgb) {
  if (!rgb) return '#000000';
  if (rgb.startsWith('#')) return rgb;
  const nums = rgb.match(/\d+/g);
  if (!nums) return '#000000';
  return '#' + nums.slice(0,3).map(n => {
    const h = parseInt(n).toString(16);
    return h.length === 1 ? '0' + h : h;
  }).join('');
}

poetryList.addEventListener('click', (e) => {
  const card = e.target.closest('.poetry-card');
  if (card) {
    const text = card.getAttribute('data-poetry');
    try {
      const decodedText = JSON.parse(text);
      addToCanvas(decodedText);
    } catch {
      addToCanvas(card.innerText);
    }
  }
});
poetryList.addEventListener('keydown', (e) => {
  if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('poetry-card')) {
    e.preventDefault();
    const text = e.target.getAttribute('data-poetry');
    try {
      const decodedText = JSON.parse(text);
      addToCanvas(decodedText);
    } catch {
      addToCanvas(e.target.innerText);
    }
  }
});

function changeFontStyle(font) {
  if (pendingSelectionItem) {
    pendingSelectionItem.style.fontFamily = font;
    currentCanvasItem = pendingSelectionItem;
    selectCanvasItem(currentCanvasItem);
  } else {
    alert("👉 Please select a text item on the canvas first.");
  }
}


function changeFontColor(color) {
  if (pendingSelectionItem) {
    pendingSelectionItem.style.color = color;
    currentCanvasItem = pendingSelectionItem;
    selectCanvasItem(currentCanvasItem);
  } else {
    alert("👉 Please select a text item on the canvas first.");
  }
}


function changeCanvasColor(color) {
  canvas.style.backgroundColor = color;
}

function toggleBold() {
  if (pendingSelectionItem) {
    const isBold = pendingSelectionItem.style.fontWeight === 'bold';
    pendingSelectionItem.style.fontWeight = isBold ? 'normal' : 'bold';
    // update currentCanvasItem to reflect change synchronously
    currentCanvasItem = pendingSelectionItem;
    selectCanvasItem(currentCanvasItem);
  } else {
    alert("👉 Please select a text item on the canvas first.");
  }
}

function toggleItalic() {
  if (pendingSelectionItem) {
    const comp = window.getComputedStyle(pendingSelectionItem).fontStyle;
    pendingSelectionItem.style.fontStyle = (comp === 'italic') ? 'normal' : 'italic';
    currentCanvasItem = pendingSelectionItem;
    selectCanvasItem(currentCanvasItem);
  } else {
    alert("👉 Please select a text item on the canvas first.");
  }
}


function toggleUnderline() {
  const target = pendingSelectionItem || currentCanvasItem;
  if (target) {
    const comp = window.getComputedStyle(target).textDecorationLine || window.getComputedStyle(target).textDecoration;
    const isUnder = comp && comp.toString().toLowerCase().includes('underline');
    target.style.textDecoration = isUnder ? 'none' : 'underline';
    currentCanvasItem = target;
    selectCanvasItem(currentCanvasItem);
  } else {
    alert("👉 Please select a text item on the canvas first.");
  }
}


function toggleTextShadow() {
  if (pendingSelectionItem) {
    pendingSelectionItem.style.textShadow = pendingSelectionItem.style.textShadow ? '' : '2px 2px 4px rgba(0,0,0,0.4)';
    currentCanvasItem = pendingSelectionItem;
    selectCanvasItem(currentCanvasItem);
  } else {
    alert("👉 Please select a text item on the canvas first.");
  }
}

function resizeText(action) {
  if (pendingSelectionItem) {
    let size = parseFloat(window.getComputedStyle(pendingSelectionItem).fontSize) || 24;
    size = (action === 'increase') ? Math.min(400, size + 2) : Math.max(8, size - 2);
    pendingSelectionItem.style.fontSize = size + 'px';
    currentCanvasItem = pendingSelectionItem;
    selectCanvasItem(currentCanvasItem);
  } else {
    alert("👉 Please select a text item on the canvas first.");
  }
}


function clearCanvas() {
  canvas.innerHTML = '';
  currentCanvasItem = null;
}


if (fontSelector) fontSelector.addEventListener('change', () => changeFontStyle(fontSelector.value));
if (fontColorInput) fontColorInput.addEventListener('input', () => changeFontColor(fontColorInput.value));

canvas.addEventListener('pointerdown', (e) => {
  if (e.target === canvas) deselectAll();
});
document.addEventListener('pointerdown', (e) => {
  if (!e.target.closest('.canvas-item') && !e.target.closest('#designCanvas')) deselectAll();
});

document.addEventListener('selectstart', (e) => {
  if (e.target.closest('.canvas-item') || e.target.closest('#designCanvas')) e.preventDefault();
});


function makeDraggable(element, container) {
  let startX = 0, startY = 0, origLeft = 0, origTop = 0, dragging = false;

  element.addEventListener('pointerdown', (e) => {
    if (e.button && e.button !== 0) return;
    if (e.target.classList.contains('resize-handle') || e.target.classList.contains('rotate-handle')) return;
    selectCanvasItem(element);
    dragging = true;
    startX = e.clientX;
    startY = e.clientY;
    origLeft = element.offsetLeft;
    origTop = element.offsetTop;
    try { element.setPointerCapture && element.setPointerCapture(e.pointerId); } catch(_){}
    function onPointerMove(ev) {
      if (!dragging) return;
      ev.preventDefault();
      const dx = ev.clientX - startX;
      const dy = ev.clientY - startY;
      let newLeft = origLeft + dx;
      let newTop = origTop + dy;
      newLeft = Math.max(0, Math.min(newLeft, container.clientWidth - element.offsetWidth));
      newTop = Math.max(0, Math.min(newTop, container.clientHeight - element.offsetHeight));
      element.style.left = newLeft + 'px';
      element.style.top = newTop + 'px';
    }
    function onPointerUp() {
      dragging = false;
      document.removeEventListener('pointermove', onPointerMove);
      document.removeEventListener('pointerup', onPointerUp);
      try { element.releasePointerCapture && element.releasePointerCapture(e.pointerId); } catch(_){}
    }
    document.addEventListener('pointermove', onPointerMove);
    document.addEventListener('pointerup', onPointerUp);
  });
}

function enableResize(handle, item) {
  let startX=0, startY=0, startSize=0;
  handle.addEventListener('pointerdown', (e) => {
    e.stopPropagation();
    e.preventDefault();
    startX = e.clientX;
    startY = e.clientY;
    startSize = parseFloat(window.getComputedStyle(item).fontSize) || defaults.fontSize;
    function onMove(ev) {
      ev.preventDefault();
      const dx = ev.clientX - startX;
      const dy = ev.clientY - startY;
      const delta = Math.round((dx + dy) / 4);
      const newSize = Math.max(8, Math.min(400, startSize + delta));
      item.style.fontSize = newSize + 'px';
    }
    function onUp() {
      document.removeEventListener('pointermove', onMove);
      document.removeEventListener('pointerup', onUp);
    }
    document.addEventListener('pointermove', onMove);
    document.addEventListener('pointerup', onUp);
  });
}

function enableRotate(handle, item) {
  let centerX=0, centerY=0, startAngle=0, initialRotation=0;
  handle.addEventListener('pointerdown', (e) => {
    e.stopPropagation();
    e.preventDefault();
    const rect = item.getBoundingClientRect();
    centerX = rect.left + rect.width / 2;
    centerY = rect.top + rect.height / 2;
    startAngle = Math.atan2(e.clientY - centerY, e.clientX - centerX) * (180 / Math.PI);
    initialRotation = parseFloat(item.dataset.rotation) || 0;
    function onMove(ev) {
      ev.preventDefault();
      const currentAngle = Math.atan2(ev.clientY - centerY, ev.clientX - centerX) * (180 / Math.PI);
      const delta = currentAngle - startAngle;
      const newRot = initialRotation + delta;
      item.dataset.rotation = newRot;
      item.style.transform = `rotate(${newRot}deg)`;
    }
    function onUp() {
      document.removeEventListener('pointermove', onMove);
      document.removeEventListener('pointerup', onUp);
    }
    document.addEventListener('pointermove', onMove);
    document.addEventListener('pointerup', onUp);
  });
}

// ======= IMAGE PANEL CLICK TO ADD IMAGES TO CANVAS ======= //
document.querySelectorAll('.image-card img').forEach(img => {
  img.addEventListener('click', () => {
    addImageToCanvas(img.src);
  });
});

function addImageToCanvas(src) {
  const img = document.createElement('img');
  img.src = src;
  img.className = 'canvas-item';
  img.style.position = 'absolute';
  img.style.left = '180px';
  img.style.top = '80px';
  img.style.width = '550px';  // initial size
  img.style.cursor = 'move';
  img.style.userSelect = 'none';
  img.dataset.rotation = '0';

  img.addEventListener('mousedown', (e) => {
    e.stopPropagation();
    selectCanvasItem(img);
  });

  img.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      selectCanvasItem(img);
    }
  });

  canvas.appendChild(img);
  makeDraggable(img, canvas);
  selectCanvasItem(img);
}


document.getElementById('downloadTemplateBtn').addEventListener('click', () => {

  const canvas = document.getElementById('designCanvas');
const canvasContent = canvas.innerHTML;
const bgColor = window.getComputedStyle(canvas).backgroundColor || '#fefefe';  // read canvas background

const htmlContent = `
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>My Custom Template</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Nastaliq+Urdu&family=Reem+Kufi&family=Amiri&family=Lateef&family=Tajawal&family=Scheherazade&family=Sakkal+Majalla&display=swap" rel="stylesheet" />
    <style>
      body {
        font-family: 'Noto Nastaliq Urdu', serif;
        padding: 20px;
        margin: 0;
        background: ${bgColor}; /* preserve background color */
      }
      .canvas-item {
        position: relative;
        display: inline-block;
        white-space: pre;
        padding: 4px 6px;
        border-radius: 6px;
      }
    </style>
  </head>
  <body>
    <div>${canvasContent}</div>
  </body>
  </html>
`;


  const blob = new Blob([htmlContent], {type: 'text/html'});
  const url = URL.createObjectURL(blob);

  const a = document.createElement('a');
  a.href = url;
  a.download = 'my_template.html';

  document.body.appendChild(a);
  a.click();

  setTimeout(() => {
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  }, 0);
});


window.addToCanvas = addToCanvas;
window.changeFontStyle = changeFontStyle;
window.changeFontColor = changeFontColor;
window.changeCanvasColor = changeCanvasColor;
window.toggleBold = toggleBold;
window.toggleItalic = toggleItalic;
window.toggleUnderline = toggleUnderline;
window.toggleTextShadow = toggleTextShadow;
window.resizeText = resizeText;
window.clearCanvas = clearCanvas;

</script>


 <!-- Footer -->
  <footer class="site-footer text-center">
      <div>Copyright © 2025 Andaz-e-Bayan | All rights reserved</div>
      <div class="footer-links">
        <a href="userCategory.php">Urdu Poetry</a> | <a href="challenges.php">Bait Bazi</a> | 
        <a href="e-commerce.php">Shop</a> | <a href="team.php">Contact</a>
      </div>
  </footer>


</body>
</html>
