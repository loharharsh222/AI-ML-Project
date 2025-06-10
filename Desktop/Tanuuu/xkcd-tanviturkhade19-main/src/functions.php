<?php

// Store verification codes temporarily
$verificationCodes = [];

/**
 * Generate a 6-digit numeric verification code.
 */
function generateVerificationCode(): string {
    return str_pad((string)rand(0, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Send a verification code to an email.
 */
function sendVerificationEmail(string $email, string $code): bool {
    global $verificationCodes;
    $verificationCodes[$email] = $code; // Store the code temporarily
    
    $subject = 'Your Verification Code';
    $body = "<p>Your verification code is: <strong>{$code}</strong></p>";
    $headers = 'From: no-reply@example.com' . "\r\n" .
               'Content-Type: text/html; charset=UTF-8' . "\r\n";
    return mail($email, $subject, $body, $headers);
}

/**
 * Send an unsubscribe confirmation code to an email.
 */
function sendUnsubscribeConfirmationEmail(string $email, string $code): bool {
    global $verificationCodes;
    $verificationCodes[$email . '_unsubscribe'] = $code; // Store the code temporarily
    
    $subject = 'Confirm Un-subscription';
    $body = "<p>To confirm un-subscription, use this code: <strong>{$code}</strong></p>";
    $headers = 'From: no-reply@example.com' . "\r\n" .
               'Content-Type: text/html; charset=UTF-8' . "\r\n";
    return mail($email, $subject, $body, $headers);
}

/**
 * Register an email by storing it in a file.
 */
function registerEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        file_put_contents($file, '');
    }
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
        return true;
    }
    return false;
}

/**
 * Unsubscribe an email by removing it from the list.
 */
function unsubscribeEmail(string $email): bool {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        return false;
    }
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (in_array($email, $emails)) {
        $emails = array_diff($emails, [$email]);
        file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
        return true;
    }
    return false;
}

/**
 * Verify if the provided code matches the sent one.
 */
function verifyCode(string $email, string $code, bool $isUnsubscribe = false): bool {
    global $verificationCodes;
    $key = $isUnsubscribe ? $email . '_unsubscribe' : $email;
    return isset($verificationCodes[$key]) && $verificationCodes[$key] === $code;
}

/**
 * Fetch random XKCD comic and format data as HTML.
 */
function fetchAndFormatXKCDData(): string {
    $randomComicId = rand(1, 2500); // Assuming XKCD has comics up to ID 2500
    $url = "https://xkcd.com/{$randomComicId}/info.0.json";
    $data = json_decode(file_get_contents($url), true);
    if (!$data) {
        return '<p>Failed to fetch XKCD comic.</p>';
    }
    
    // Create proper unsubscribe URL with email parameter
    $unsubscribeUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/unsubscribe.php';
    return "<h2>XKCD Comic</h2><img src='{$data['img']}' alt='XKCD Comic'><p><a href='{$unsubscribeUrl}' id='unsubscribe-button'>Unsubscribe</a></p>";
}

/**
 * Send the formatted XKCD updates to registered emails.
 */
function sendXKCDUpdatesToSubscribers(): void {
    $file = __DIR__ . '/registered_emails.txt';
    if (!file_exists($file)) {
        return;
    }
    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $xkcdData = fetchAndFormatXKCDData();
    $subject = 'Your XKCD Comic';
    $headers = 'From: no-reply@example.com' . "\r\n" .
               'Content-Type: text/html; charset=UTF-8' . "\r\n";
    foreach ($emails as $email) {
        // Add email to unsubscribe URL
        $emailData = str_replace('unsubscribe.php', 'unsubscribe.php?email=' . urlencode($email), $xkcdData);
        mail($email, $subject, $emailData, $headers);
    }
}
