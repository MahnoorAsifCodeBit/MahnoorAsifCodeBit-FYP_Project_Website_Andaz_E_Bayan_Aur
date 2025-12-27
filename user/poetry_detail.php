<?php
include '../config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../userLogin.php");
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





if (!isset($_GET['id'])) {
    echo "Poetry ID missing!";
    exit();
}

$poetry_id = (int)$_GET['id'];

// Increment view count
$conn->query("UPDATE poetry_content SET view_count = view_count + 1 WHERE content_id = $poetry_id");

// Get poetry details
$poetry_query = $conn->query("SELECT p.*, c.title AS category_title FROM poetry_content p 
JOIN categories c ON p.category_id = c.id 
WHERE p.content_id = $poetry_id");

$poem = $poetry_query->fetch_assoc();

if (!$poem) {
    echo "<h2>Poetry not found.</h2>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $poem['title']; ?> - Andaz-e-Bayan Aur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Gulzar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="poetry_detail.css">
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
            <img src="<?php echo $profile_pic; ?>" class="nav-image"> &nbsp; <?php echo htmlspecialchars($user_name); ?>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-5">
    <h2><?php echo $poem['title']; ?></h2>
    <p><strong>Category:</strong> <?php echo $poem['category_title']; ?></p>
    <div class="border rounded p-3" style="background-color: #f8f9fa;">
        <pre style="white-space: pre-wrap;"><?php echo $poem['text']; ?></pre>
    </div>
    <div class="mt-4">
        <a href="category_poetry.php?category_id=<?php echo $poem['category_id']; ?>" class="btn btn-secondary">Back to Category</a>
    </div>
</div>

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
