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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="registration-container">
        <h2 class="text-center">Register</h2>

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

            <div class="mb-3">
                <label for="reg_password" class="form-label">Password</label>
                <input type="password" class="form-control" id="reg_password" name="reg_password" required placeholder="Enter your password">
            </div>

            <div class="mb-3">
                <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
            </div>
        </form>

        <p class="text-center">Already have an account? <a href="login.php">Login here</a></p>

        <!-- Footer -->
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
