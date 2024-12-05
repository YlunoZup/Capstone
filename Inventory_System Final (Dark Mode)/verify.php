<?php
// Start session and include database configuration
session_start();
include 'config.php';

// Check if the verification code is provided in the URL
if (isset($_GET['code'])) {
    $verification_code = $_GET['code'];

    // Validate the verification code
    if (!preg_match('/^[a-zA-Z0-9]+$/', $verification_code)) {
        $error = 'Invalid verification code format.';
    } else {
        // Query to check if the verification code exists in the database
        $stmt = $conn->prepare('SELECT * FROM users WHERE verification_code = ? AND is_verified = 0');
        $stmt->bind_param('s', $verification_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // If a match is found, update the user's status to verified
            $update_stmt = $conn->prepare('UPDATE users SET is_verified = 1 WHERE verification_code = ?');
            $update_stmt->bind_param('s', $verification_code);
            if ($update_stmt->execute()) {
                $success = 'Your email has been successfully verified! You can now log in.';
            } else {
                $error = 'Verification failed. Please try again later.';
            }
        } else {
            $error = 'Invalid verification code or the email has already been verified.';
        }
    }
} else {
    $error = 'No verification code provided.';
}
?>