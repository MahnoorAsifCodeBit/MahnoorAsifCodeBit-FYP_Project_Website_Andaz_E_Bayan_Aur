<?php
include 'config.php';
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admins WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);
    $admin = mysqli_fetch_assoc($result);

    if ($admin) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_name'] = $admin['name'];
        header("Location: dashboard.php");
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="log.css">
</head>
<body>
    <div class="login-box">
        <h2>Admin Login Portal</h2>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required><br>
            <div class="password-container">
                 <input type="password" id="password" name="password" placeholder="Password" required>
                 <span class="toggle-password" onclick="togglePassword()">👁 Show</span>
            </div>
            <button type="submit" name="login">Login</button>
            <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        </form>
    </div>
</body>
</html>


