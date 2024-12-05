<?php
include 'config.php';

$response = ['success' => false, 'error' => '', 'data' => null];

// Handle GET request to fetch dish details
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "SELECT * FROM dishes WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $response['success'] = true;
        $response['data'] = mysqli_fetch_assoc($result);
    } else {
        $response['error'] = 'Dish not found.';
    }
}

// Handle POST request to update dish details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);

    $query = "UPDATE dishes SET name = '$name', description = '$description', price = $price, category = '$category', quantity = $quantity WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        $response['success'] = true;
    } else {
        $response['error'] = 'Failed to update the dish.';
    }
}

echo json_encode($response);
exit;
