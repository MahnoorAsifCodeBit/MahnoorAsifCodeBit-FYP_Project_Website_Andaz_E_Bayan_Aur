<?php
include 'config.php'; // database connection
include 'mailer_config.php'; // PHPMailer setup

$message = ""; // to store success or error message

if (isset($_POST['email'])) {
    $user_email = mysqli_real_escape_string($conn, $_POST['email']);
    $check_user = mysqli_query($conn, "SELECT * FROM users WHERE email='$user_email'");
    
    if (mysqli_num_rows($check_user) > 0) {
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // token valid for 1 hour

        // Save token in DB
        mysqli_query($conn, "UPDATE users SET reset_token='$token', reset_token_expiry='$expiry' WHERE email='$user_email'");

        $reset_link = "http://localhost/andazebayan-admin/reset_password.php?token=" . $token;

        sendMail(
            $user_email, 
            'Password Reset Request', 
            'Click the link below to reset your password:<br><a href="' . $reset_link . '">Reset Your Password</a><br>This link is valid for 1 hour.'
        );
        
        $message = "<span style='color: green;'>Password reset email sent! Please check your inbox.</span>";
    } else {
        $message = "<span style='color: red;'>No user found with this email address.</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('your-background-image.jpg') no-repeat center center/cover;
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dialog-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            width: 350px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: popIn 0.4s ease;
        }

        .dialog-container h2 {
            color: #a30000;
            margin-bottom: 25px;
        }

        .dialog-container input[type="email"] {
            width: 100%;
            padding: 12px;
            margin: 15px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .dialog-container button {
            background-color: #a30000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .dialog-container button:hover {
            background-color: #750000;
        }

        .message {
            margin-top: 20px;
            font-weight: 500;
        }

        @keyframes popIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .back-to-login {
            margin-top: 20px;
        }

        .back-to-login a {
            color: #a30000;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        .back-to-login a:hover {
            color: #750000;
        }
    </style>
</head>
<body>
<div class="overlay">
    <div class="dialog-container">
        <h2>Forgot Password</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit">Send Reset Link</button>
        </form>
        <?php if (!empty($message)) { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>
        <div class="back-to-login">
            <a href="userlogin.php">← Back to Login</a>
        </div>
    </div>
</div>
</body>
</html>
