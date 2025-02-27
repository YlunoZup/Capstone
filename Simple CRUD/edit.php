<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Check if ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Retrieve limit from URL, default to 10 if not set
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

$id = $_GET['id'];
$query = "SELECT * FROM clients WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$client = mysqli_fetch_assoc($result);

if (!$client) {
    header('Location: index.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if (empty($name) || empty($email)) {
        $error = "Name and Email are required.";
    } else {
        $updateQuery = "UPDATE clients SET name=?, email=?, phone=?, address=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ssssi", $name, $email, $phone, $address, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: index.php?limit=$limit"); // Pass limit back
            exit;
        } else {
            $error = "Error updating client: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <div class="navbar-nav">
                <a class="nav-link" href="index.php?limit=<?php echo $limit; ?>">Home</a>
            </div>
            <a href="logout.php" class="btn btn-outline-light ms-auto">Logout</a>
        </div>
    </nav>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-sm form-container">
            <h2 class="text-center mb-4">Edit Client</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($client['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($client['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($client['phone']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($client['address']); ?>">
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="index.php?limit=<?php echo $limit; ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
