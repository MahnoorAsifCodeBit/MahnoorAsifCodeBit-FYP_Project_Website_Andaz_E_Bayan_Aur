<?php
include 'config.php'; // Ensure this file connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $contact = trim($_POST['contact']);
    $errors = [];

    // Validate Name (only letters and spaces allowed)
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $errors[] = "Name can only contain letters and spaces.";
    }

    // Validate Email (proper format)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate Contact Number (10-15 digits)
    if (!preg_match("/^\d{10,15}$/", $contact)) {
        $errors[] = "Contact number must be 10-15 digits.";
    }

    // Validate Password (minimum 6 chars, 1 number, 1 uppercase)
    if (strlen($password) < 6 || !preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $errors[] = "Password must be at least 6 characters long, contain a number, and an uppercase letter.";
    }

    // Confirm Password Validation
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $errors[] = "This email is already registered. Please login or use another email.";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            // Insert User Data
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, contact) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $contact);
            
            if ($stmt->execute()) {
                $success = "Registration successful! <a href='userlogin.php'>Login now</a>";
            } else {
                $errors[] = "Something went wrong. Please try again.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link rel="stylesheet" href="userReg.css">
</head>
<body>
    <div class="login-box">
        <h2>Register</h2>
        
        <?php 
        if (!empty($errors)) {
            echo "<div class='message'>" . implode("<br>", $errors) . "</div>";
        } 
        if (isset($success)) {
            echo "<div class='success'>$success</div>";
        }
        ?>
        
        <form method="post">
            <input type="text" name="name" placeholder="Full Name" required value="<?= htmlspecialchars($name ?? '') ?>"><br>
            <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($email ?? '') ?>"><br>
            <input type="text" name="contact" placeholder="Contact Number" required value="<?= htmlspecialchars($contact ?? '') ?>"><br>
            
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePassword()">👁 Show</span>
            </div>
            
            <div class="password-container">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <span class="toggle-password" onclick="toggleConfirmPassword()">👁 Show</span>
            </div>
            
            <button type="submit">Register</button>
        </form>
        
        <p>Already have an account? <a href="userlogin.php">Login here</a></p>
    </div>
</body>
</html>

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

function toggleConfirmPassword() {
    var x = document.getElementById("confirm_password");
    var toggleText = document.querySelectorAll(".toggle-password")[1];
    if (x.type === "password") {
        x.type = "text";
        toggleText.textContent = "🙈 Hide";
    } else {
        x.type = "password";
        toggleText.textContent = "👁 Show";
    }
}
</script>
