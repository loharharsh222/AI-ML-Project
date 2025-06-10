<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && !isset($_POST['verification_code'])) {
        $email = $_POST['email'];
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $code = generateVerificationCode();
            if (sendVerificationEmail($email, $code)) {
                echo "<p>Verification code sent to {$email}. Please check your email.</p>";
            } else {
                echo "<p>Failed to send verification code.</p>";
            }
        } else {
            echo "<p>Invalid email address.</p>";
        }
    } elseif (isset($_POST['verification_code'])) {
        $email = $_POST['email'];
        $code = $_POST['verification_code'];
        if (verifyCode($email, $code)) {
            if (registerEmail($email)) {
                echo "<p>Email successfully registered!</p>";
            } else {
                echo "<p>Email already registered.</p>";
            }
        } else {
            echo "<p>Invalid verification code.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XKCD Registration</title>
</head>
<body>
    <h1>Register for XKCD Comics</h1>
    
    <!-- Email Registration Form -->
    <form method="POST">
        <input type="email" name="email" required placeholder="Enter your email">
        <button id="submit-email">Submit</button>
    </form>

    <!-- Verification Code Form -->
    <form method="POST">
        <input type="email" name="email" required placeholder="Enter your email">
        <input type="text" name="verification_code" maxlength="6" required placeholder="Enter verification code">
        <button id="submit-verification">Verify</button>
    </form>
</body>
</html>
