<?php
// delete.php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Delete the dish from the database
    $query = "DELETE FROM dishes WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header("Location: index.php"); // Redirect to the inventory page after deletion
    } else {
        echo "Error deleting the dish.";
    }
}
?>
