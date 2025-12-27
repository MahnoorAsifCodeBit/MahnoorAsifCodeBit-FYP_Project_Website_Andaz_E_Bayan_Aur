<?php
include 'config.php'; // database connection



if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $check_token = mysqli_query($con, "SELECT * FROM users WHERE reset_token='$token' AND reset_token_expiry > NOW()");

    if (mysqli_num_rows($check_token) > 0) {
        if (isset($_POST['password'])) {
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            mysqli_query($con, "UPDATE users SET password='$new_password', reset_token=NULL, reset_token_expiry=NULL WHERE reset_token='$token'");
            echo "Your password has been updated successfully!";
            exit;
        }
        ?>
        <form method="POST">
            <input type="password" name="password" placeholder="Enter new password" required>
            <button type="submit">Reset Password</button>
        </form>
        <?php
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token found.";
}
?>
