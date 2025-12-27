<?php
session_start(); 
include '../config.php';


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


if (!isset($_GET['category_id'])) {
    echo "Category ID missing!";
    exit();
}

$category_id = $_GET['category_id'];

// Get category name
$category_query = $conn->query("SELECT title FROM categories WHERE id = $category_id");
$category = $category_query->fetch_assoc();

if (!$category) {
    echo "<h2>Invalid category selected.</h2>";
    exit();
}

// Get poetry under this category
$poetry_query = $conn->query("SELECT * FROM poetry_content WHERE category_id = $category_id");



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $category['title']; ?> - Poetry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="category_Poetry.css">
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
        <h2> <?php echo $category['title']; ?> Poetry </h2>
        <div class="row">
            <?php while ($poem = $poetry_query->fetch_assoc()) { ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $poem['title']; ?></h5>
                            <p class="card-text"><?php echo substr($poem['text'], 0, 100); ?>...</p>
                            <a href="track_poetry_click.php?poetry_id=<?php echo $poem['content_id']; ?>" class="btn btn-primary">Read More</a>

                        </div>
                    </div>
                </div>
            <?php } ?>
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
