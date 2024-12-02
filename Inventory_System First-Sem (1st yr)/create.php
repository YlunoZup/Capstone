<?php
include 'config.php';  // Include your database configuration

$response = ['success' => false, 'error' => '', 'data' => null];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the form
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

    // Validate the input fields
    if (empty($name) || empty($description) || empty($category) || empty($price) || empty($quantity)) {
        $response['error'] = 'All fields are required!';
    } else {
        // SQL query to insert the new dish
        $query = "INSERT INTO dishes (name, description, category, price, quantity) 
                  VALUES ('$name', '$description', '$category', '$price', '$quantity')";

        if (mysqli_query($conn, $query)) {
            $response['success'] = true;
            $response['data'] = [
                'name' => $name,
                'description' => $description,
                'category' => $category,
                'price' => $price,
                'quantity' => $quantity,
            ];
        } else {
            $response['error'] = 'Failed to add the dish. Please try again.';
        }
    }
} else {
    $response['error'] = 'Invalid request!';
}

echo json_encode($response);  // Return the response in JSON format
exit;
