<?php
session_start();

include 'config.php';

$error = '';

function encrypt($data, $key) {
    return openssl_encrypt($data, 'aes-256-cbc', $key, 0, substr(hash('sha256', $key), 0, 16));
}

function decrypt($data, $key) {
    return openssl_decrypt($data, 'aes-256-cbc', $key, 0, substr(hash('sha256', $key), 0, 16));
}

$encryption_key = "your_secure_key";

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

            // Handle Remember Me functionality
            if (isset($_POST['remember'])) {
                // Encrypt username and password before storing them in cookies
                setcookie('remember_username', encrypt($username, $encryption_key), time() + (86400 * 30), "/"); // 30 days
                setcookie('remember_password', encrypt($password, $encryption_key), time() + (86400 * 30), "/"); // 30 days
            } else {
                // Expire cookies if "Remember Me" is unchecked
                setcookie('remember_username', '', time() - 3600, "/");
                setcookie('remember_password', '', time() - 3600, "/");
            }

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

// Pre-fill username and password if cookies exist
$storedUsername = isset($_COOKIE['remember_username']) ? decrypt($_COOKIE['remember_username'], $encryption_key) : '';
$storedPassword = isset($_COOKIE['remember_password']) ? decrypt($_COOKIE['remember_password'], $encryption_key) : '';

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: url('css/chrstmas.gif') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            color: #fff;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1;
        }

        .login-container {
            background: rgba(30, 30, 47, 0.9);
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.5);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 2;
        }

        .login-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #fff;
        }

        .form-label {
            color: #ccc;
        }

        .form-control {
            background: #292a3b;
            border: 1px solid #444;
            border-radius: 10px;
            color: #fff;
        }

        .form-control:focus {
            border-color: #007bff;
            background: #2e2f48;
            color: #fff;
        }

        /* Animated Circling Border Effect */
        @keyframes animate-border {
            0% {
                border-color: transparent;
                border-width: 2px;
            }
            25% {
                border-color: #007bff;
                border-width: 2px;
            }
            50% {
                border-color: transparent;
                border-width: 2px;
            }
            75% {
                border-color: #007bff;
                border-width: 2px;
            }
            100% {
                border-color: transparent;
                border-width: 2px;
            }
        }

        .btn-primary {
            border-radius: 25px;
            background: transparent;
            border: 2px solid transparent;
            animation: animate-border 2s linear infinite; /* Apply animation to border */
            color: #007bff;
            padding: 12px 25px;
        }

        .btn-primary:hover {
            background: #007bff;
            color: white;
            border-color: #0056b3;
        }

        .footer {
            margin-top: 20px;
            font-size: 0.85rem;
            text-align: center;
            color: #888;
        }

        .footer-link {
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
        }

        .footer-link:hover {
            color: #0056b3;
            text-shadow: 0 0 5px rgba(0, 123, 255, 0.6);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-title">Inventory Login</div>

     <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

        <form method="POST" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input 
                type="text" 
                class="form-control" 
                id="username" 
                name="username" 
                required 
                placeholder="Enter your username" 
                value="<?php echo htmlspecialchars($storedUsername); ?>">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input 
                type="password" 
                class="form-control" 
                id="password" 
                name="password" 
                required 
                placeholder="Enter your password" 
                value="<?php echo htmlspecialchars($storedPassword); ?>">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="remember" name="remember" 
               <?php echo isset($_COOKIE['remember_username']) ? 'checked' : ''; ?>>
            <label for="remember" class="form-check-label">Remember Me</label>
        </div>

        <div class="d-grid">
        <button type="submit" name="login" class="btn btn-primary">Login</button>
        </div>
    </form>


        <style>
        #username::placeholder,
        #password::placeholder {
        color: rgba(0, 0, 0, 0.5); /* Lighter shade */
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2); /* Subtle shadow effect */
        }   
        </style>

        <p class="text-center mt-3">Don't have an account? <a href="register.php" class="footer-link">Create account</a></p>

        <footer class="footer">
            All Rights Reserved &copy;
            <a href="https://github.com/YlunoZup" target="_blank" class="footer-link">YlunoZup</a>.
        </footer>
    </div>
</body>
</html>
