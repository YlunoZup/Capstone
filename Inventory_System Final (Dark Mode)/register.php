<?php
// Include the database connection config
include('config.php');

// Initialize variables to avoid undefined variable warnings
$error = null;
$success = null;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = trim($_POST['reg_username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['reg_password']);

    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Check if the username or email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // For now, we set is_verified as 0 and verification_code as NULL
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, verification_code, is_verified) VALUES (?, ?, ?, NULL, 0)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $success = "Registration successful! You can now log in.";
            } else {
                $error = "Error during registration: " . $stmt->error;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            color: #84C9FB;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(24, 36, 43, 0.7); /* Dark overlay */
            z-index: 1;
        }

        .registration-container {
            background: rgba(24, 36, 43, 0.9); /* Dark theme with transparent effect */
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.5);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            position: relative;
            z-index: 2;
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
            border-color: #84C9FB;
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
            color: #84C9FB;
            font-weight: bold;
            text-decoration: none;
        }

        .footer-link:hover {
            color: #66A7D4;
            text-shadow: 0 0 5px rgba(0, 123, 255, 0.6);
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            background: rgba(0, 0, 0, 0.7); /* Dark overlay */
            z-index: 1;
        }

        .registration-container {
            background: rgba(30, 30, 47, 0.9); /* Semi-transparent dark background */
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

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2 class="text-center" style="color: #fff;">Register Now</h2>

        <!-- Display error message -->
        <?php if (!is_null($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <!-- Display success message -->
        <?php if (!is_null($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="mb-3">
                <label for="reg_username" class="form-label">Username</label>
                <input type="text" class="form-control" id="reg_username" name="reg_username" required placeholder="Enter your username">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
            </div>

            <div class="mb-3 password-container">
                <label for="reg_password" class="form-label">Password</label>
                <input type="password" class="form-control" id="reg_password" name="reg_password" required placeholder="Enter your password">
                <span class="password-toggle" onclick="togglePassword()">
                    <i id="password-icon" class="bi bi-eye"></i>
                </span>
            </div>

            <div class="mb-3">
                <button type="submit" name="register" class="btn btn-primary w-100">Sign Up</button>
            </div>
        </form>

        <style>
        #reg_username::placeholder,
        #email::placeholder,
        #reg_password::placeholder {
        color: rgba(0, 0, 0, 0.5); /* Lighter shade */
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2); /* Subtle shadow effect */
        }
        </style>

        <p class="text-center mt-3">Already have an account? <a href="login.php" class="footer-link">Login</a></p>

        <footer class="footer">
            All Rights Reserved &copy;
            <a href="https://github.com/YlunoZup" target="_blank" class="footer-link">YlunoZup</a>.
        </footer>
    </div>
</body>
</html>