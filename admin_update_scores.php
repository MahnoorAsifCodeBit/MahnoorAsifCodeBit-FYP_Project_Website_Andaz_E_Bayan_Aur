<?php
include 'config.php';
session_start();


if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];

if (!isset($_GET['participant_id']) || empty($_GET['participant_id'])) {
    echo "<script>alert('Invalid Participant ID'); window.location='admin_challenges.php';</script>";
    exit();
}

$participant_id = (int) $_GET['participant_id'];

$query = "
    SELECT challenge_participants.id, users.name, challenge_participants.entry_text, challenge_participants.score, challenge_participants.challenge_id
    FROM challenge_participants
    JOIN users ON challenge_participants.user_id = users.id
    WHERE challenge_participants.id = $participant_id";
$result = mysqli_query($conn, $query);
$participant = mysqli_fetch_assoc($result);

if (!$participant) {
    echo "<script>alert('Participant not found'); window.location='admin_challenges.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_score = (int) $_POST['score'];
    $update_query = "UPDATE challenge_participants SET score = $new_score WHERE id = $participant_id";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Score updated successfully!'); window.location='admin_challenge_participants.php?id={$participant['challenge_id']}';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Score - <?= htmlspecialchars($participant['name']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f5f6fa;
      color: #333;
      margin: 0;
    }

    /* Sidebar */
    .sidebar {
      width: 260px;
      background: linear-gradient(180deg, #1a1d2b, #23263a);
      color: #fff;
      position: fixed;
      top: 0; bottom: 0; left: 0;
      padding: 20px 0;
      display: flex;
      flex-direction: column;
      box-shadow: 4px 0 20px rgba(0,0,0,0.15);
      z-index: 100;
    }
    .sidebar h2 {
      text-align: center;
      font-size: 1.5rem;
      margin-bottom: 30px;
      font-weight: 700;
    }
    .sidebar a {
      padding: 14px 25px;
      display: block;
      color: #bbb;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .sidebar a:hover, .sidebar a.active {
      background: rgba(255,255,255,0.1);
      color: #fff;
    }

    /* Main content */
    .main-content {
      margin-left: 260px;
      padding: 20px;
    }

    /* Topbar */
    .topbar {
      background: #fff;
      padding: 15px 25px;
      border-radius: 12px;
      margin-bottom: 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      position: sticky;
      top: 0;
      z-index: 99;
    }

    /* Breadcrumb */
    .breadcrumb {
      background: none;
      justify-content: start;
      padding-left: 0;
    }
    .breadcrumb a {
      text-decoration: none;
      color: #800000;
    }
    .breadcrumb .active {
      color: #555;
    }

    /* Page Header */
    .page-header {
      margin: 30px auto 20px;
      text-align: center;
    }
    .page-header h2 {
      font-weight: 700;
      color: #222;
    }

    /* Layout */
    .container-main {
      display: flex;
      justify-content: center;
      gap: 40px;
      flex-wrap: wrap;
      margin-bottom: 50px;
    }

    /* Poetry Card */
    .poetry-card {
      background: #fff;
      border-radius: 12px;
      padding: 25px;
      max-width: 600px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      overflow-x: auto;
    }
    .poetry-card h5 {
      margin-bottom: 15px;
      font-weight: 600;
      color: #800000;
    }

    /* Score Card */
    .score-card {
      background: #fff;
      border-radius: 12px;
      padding: 25px;
      max-width: 350px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    }
    .score-card label {
      font-weight: 600;
      font-size: 16px;
      margin-bottom: 8px;
      display: block;
    }
    .score-card input[type="number"] {
      width: 100%;
      padding: 12px;
      font-size: 15px;
      border-radius: 8px;
      border: 1px solid #ccc;
      margin-bottom: 20px;
    }
    .score-card button {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      font-weight: 600;
      background-color: #800000;
      color: #fff;
      border: none;
      border-radius: 10px;
      transition: all 0.3s;
    }
    .score-card button:hover {
      background-color: #a83232;
      transform: translateY(-2px);
    }

    /* Back button */
    .back-btn {
      margin-bottom: 20px;
    }

    @media (max-width: 768px) {
      .container-main {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php"><i class="fa fa-home me-2"></i>Dashboard</a>
    <a href="add-category.php"><i class="fa fa-plus me-2"></i>Add Category</a>
    <a href="view-category.php"><i class="fa fa-list me-2"></i>View Categories</a>
    <a href="add_poetry.php"><i class="fa fa-pen me-2"></i>Add Poetry</a>
    <a href="view_poetry.php"><i class="fa fa-book me-2"></i>View Poetry</a>
    <a href="user_list.php"><i class="fa fa-users me-2"></i>Manage Users</a>
    <a href="analytics.php"><i class="fa fa-chart-line me-2"></i>Analytics</a>
    <a href="admin_add_challenge.php"><i class="fa fa-swords me-2"></i>Add Challenges</a>
    <a href="admin_challenges.php"><i class="fa fa-flag me-2"></i>View Challenges</a>
    <a href="orders.php"><i class="fa fa-box me-2"></i>Orders</a>
    <a href="admin_products.php"><i class="fa fa-shopping-cart me-2"></i>Calligraphy Products</a>
    <a href="change-password.php"><i class="fa fa-key me-2"></i>Change Password</a>
    <a href="logout.php"><i class="fa fa-sign-out me-2"></i>Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">

    <!-- Topbar -->
    <div class="topbar">
      <h5 class="mb-0">Welcome, <?php echo htmlspecialchars($admin_name); ?></h5>
      <div>
        <a href="admin_challenges.php" class="btn btn-sm btn-outline-maroon me-2"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="dashboard.php" class="btn btn-sm btn-maroon me-2"><i class="fas fa-home"></i> Home</a>
        <a href="logout.php" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>

<div class="container mt-4">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="admin_challenge_participants.php?id=<?= $participant['challenge_id']; ?>">Participants</a></li>
      <li class="breadcrumb-item active" aria-current="page">Update Score</li>
    </ol>
  </nav>

  <div class="page-header">
    <h2>Update Score for <?= htmlspecialchars($participant['name']); ?></h2>
  </div>

  <div class="container-main">
    <!-- Poetry Entry -->
    <div class="poetry-card">
      <h5>Poetry Entry</h5>
      <p><?= nl2br(htmlspecialchars($participant['entry_text'])); ?></p>
    </div>

    <!-- Score Update Form -->
    <div class="score-card">
      <form method="POST">
        <label for="score">New Score</label>
        <input type="number" name="score" id="score" value="<?= $participant['score']; ?>" required>
        <button type="submit"><i class="fas fa-save me-1"></i> Update Score</button>
      </form>
      <a href="admin_challenge_participants.php?id=<?= $participant['challenge_id']; ?>" class="btn btn-outline-secondary mt-3 w-100"><i class="fas fa-arrow-left me-1"></i> Back to Participants</a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
