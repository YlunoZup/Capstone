<?php
// update_quantity.php
include 'config.php';  // Include database connection

if (isset($_POST['id']) && isset($_POST['quantity'])) {
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];

    // Update the quantity in the database
    $query = "UPDATE dishes SET quantity = $quantity WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "Quantity updated successfully!";
    } else {
        echo "Error updating quantity.";
    }
}
?>
