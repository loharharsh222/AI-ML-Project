<?php
require_once 'functions.php';

// Get email from URL parameter if available
$email = $_GET['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['unsubscribe_email']) && !isset($_POST['verification_code'])) {
        $email = $_POST['unsubscribe_email'];
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $code = generateVerificationCode();
            if (sendUnsubscribeConfirmationEmail($email, $code)) {
                echo "<p>Unsubscribe verification code sent to {$email}. Please check your email.</p>";
            } else {
                echo "<p>Failed to send unsubscribe verification code.</p>";
            }
        } else {
            echo "<p>Invalid email address.</p>";
        }
    } elseif (isset($_POST['verification_code'])) {
        $email = $_POST['unsubscribe_email'];
        $code = $_POST['verification_code'];
        if (verifyCode($email, $code, true)) {
            if (unsubscribeEmail($email)) {
                echo "<p>Successfully unsubscribed!</p>";
            } else {
                echo "<p>Email not found in subscription list.</p>";
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
    <title>Unsubscribe from XKCD</title>
</head>
<body>
    <h1>Unsubscribe from XKCD Comics</h1>
    
    <!-- Unsubscribe Email Form -->
    <form method="POST">
        <input type="email" name="unsubscribe_email" required placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>">
        <button id="submit-unsubscribe">Unsubscribe</button>
    </form>

    <!-- Unsubscribe Verification Form -->
    <form method="POST">
        <input type="email" name="unsubscribe_email" required placeholder="Enter your email" value="<?php echo htmlspecialchars($email); ?>">
        <input type="text" name="verification_code" maxlength="6" required placeholder="Enter verification code">
        <button id="submit-verification">Verify Unsubscribe</button>
    </form>
</body>
</html>
