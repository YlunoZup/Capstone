<?php
session_start();
include 'config.php';

// Initialize messages
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['reg_username']);
    $email = trim($_POST['reg_email']);
    $password = trim($_POST['reg_password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Securely hash password

    // Check if username or email exists
    $stmt = $conn->prepare("SELECT username FROM login WHERE username = ? OR email = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "Username or email already taken";
    } else {
        // Insert new user with hashed password
        $stmt = $conn->prepare("INSERT INTO login (username, email, password_hash) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        if ($stmt->execute()) {
            header('Location: login.php?register_success=1');
            exit;
        } else {
            $error = "Registration failed: " . $stmt->error;
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h2 class="text-center mb-4">Admin Registration</h2>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="reg_username" placeholder="Username" required>
                    </div>
                    
                    <div class="mb-3">
                        <input type="email" class="form-control" name="reg_email" placeholder="Email" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" class="form-control" name="reg_password" placeholder="Password" required>
                    </div>

                    <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                </form>

                <p class="text-center mt-3">
                    Already have an account? <a href="login.php">Login here</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>

