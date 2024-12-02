<?php
session_start();

include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/portal.css">
</head>
<body>
    <div class="login-container">
        <div class="login-title">Admin Login</div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required placeholder="Enter your username">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
            </div>

            <div class="d-grid">
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </div>
        </form>

        <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a></p>

        <footer class="footer">
        All Rights Reserved &copy;
    <a href="https://github.com/YlunoZup" target="_blank" class="footer-link">YlunoZup</a>.
</footer>

<style>
    .footer-link {
        color: #007bff; /* Distinct link color */
        font-weight: bold; /* Make it stand out */
        text-decoration: none; /* Remove underline */
        transition: color 0.3s ease, text-shadow 0.3s ease; /* Smooth hover effect */
    }

    .footer-link:hover {
        color: #0056b3; /* Darker shade on hover */
        text-shadow: 0px 0px 5px rgba(0, 123, 255, 0.6); /* Add glowing effect */
        text-decoration: underline; /* Optional underline on hover */
    }
</style>

    </div>
</body>
</html>
