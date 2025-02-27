<?php
// Include database connection
include "config.php";

// Check if 'id' is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare a DELETE statement
    $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect back to the main page with a success message
        header("Location: index.php?message=Client deleted successfully");
        exit;
    } else {
        // Redirect back with an error message
        header("Location: index.php?error=Error deleting client");
        exit;
    }
} else {
    // Redirect back if ID is not valid
    header("Location: index.php?error=Invalid request");
    exit;
}

