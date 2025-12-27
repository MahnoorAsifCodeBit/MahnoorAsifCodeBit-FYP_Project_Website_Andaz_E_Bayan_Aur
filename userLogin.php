<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Fetch user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // If user exists
    if ($user) {
        // ✅ Check if user account is disabled
        if ($user['status'] == 0) {
            $error = "Your account has been disabled by the admin.";
        }
        // Check if locked
        elseif ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            $error = "Account locked. Try again after " . $user['locked_until'];
        } else {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Reset attempts
                $conn->query("UPDATE users SET login_attempts = 0, locked_until = NULL WHERE id = " . $user['id']);
                
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                // Log success
                $log_stmt = $conn->prepare("INSERT INTO login_logs (user_id, email, ip_address, success) VALUES (?, ?, ?, 1)");
                $log_stmt->bind_param("iss", $user['id'], $email, $ip_address);
                $log_stmt->execute();

                header("Location: index.php");
                exit();
            } else {
                // Increase failed attempts
                $attempts = $user['login_attempts'] + 1;
                $lock_time = NULL;

                if ($attempts >= 5) {
                    $lock_time = date("Y-m-d H:i:s", strtotime("+15 minutes"));
                }
                
                $stmt_update = $conn->prepare("UPDATE users SET login_attempts = ?, last_attempt = NOW(), locked_until = ? WHERE id = ?");
                $stmt_update->bind_param("isi", $attempts, $lock_time, $user['id']);
                $stmt_update->execute();

                // Log failure
                $log_stmt = $conn->prepare("INSERT INTO login_logs (user_id, email, ip_address, success) VALUES (?, ?, ?, 0)");
                $log_stmt->bind_param("iss", $user['id'], $email, $ip_address);
                $log_stmt->execute();

                $error = ($lock_time) ? "Too many attempts. Account locked for 15 minutes." : "Invalid email or password.";
            }
        }
    } else {
        // Log unknown email attempt
        $log_stmt = $conn->prepare("INSERT INTO login_logs (user_id, email, ip_address, success) VALUES (NULL, ?, ?, 0)");
        $log_stmt->bind_param("ss", $email, $ip_address);
        $log_stmt->execute();

        $error = "Invalid email or password.";
    }
}
?>


<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <link rel="stylesheet" href="userLog.css">
</head>
<body>
<div class="login-box">
    <h2>User Login</h2>
    <?php if(isset($error)) echo "<div class='error-box'>$error</div>"; ?>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required><br>
        <div class="password-container">
    <input type="password" id="password" name="password" placeholder="Password" required>
    <span class="toggle-password" onclick="togglePassword()">👁 Show</span>
    </div>
        <button type="submit" name="login">Login</button>
        <p class="forgot-link"><a href="forgot_password.php">Forgot Password?</a></p>
        <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
        <p class="register-link">Admin account? <a href="login.php">Login here</a></p>
    </form>
</div>

<script>
function togglePassword() {
    var x = document.getElementById("password");
    var toggleText = document.querySelector(".toggle-password");
    if (x.type === "password") {
        x.type = "text";
        toggleText.textContent = "🙈 Hide";
    } else {
        x.type = "password";
        toggleText.textContent = "👁 Show";
    }
}
</script>

