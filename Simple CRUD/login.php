<?php
session_start();
include 'config.php';

// Check if already logged in
if (isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

// Initialize error variable
$error = '';

// Check for registration success
$register_success = isset($_GET['register_success']) && $_GET['register_success'] == 1;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepared statement to fetch user
    $stmt = $conn->prepare("SELECT user_id, username, password_hash FROM login WHERE username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify hashed password
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "Username not found";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h2 class="text-center mb-4">Admin Login</h2>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>

                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                </form>

                <p class="text-center mt-3">
                    Don't have an account? <a href="register.php">Register here</a>
                </p>
            </div>
        </div>
    </div>

    <?php if ($register_success): ?>
    <script>
        alert("Registration successful! You can now log in.");
    </script>
    <?php endif; ?>
</body>
</html>

