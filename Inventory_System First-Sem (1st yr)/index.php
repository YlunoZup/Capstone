<?php
session_start();
include 'config.php';

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Pagination and search variables
$limit = isset($_GET['limit']) ? $_GET['limit'] : 5; // Entries per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page
$offset = ($page - 1) * $limit; // Offset calculation for SQL query
$search = ''; // Default empty search
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC'; // Sorting direction (default is descending)

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $searchQuery = "WHERE name LIKE '%$search%' OR description LIKE '%$search%' OR category LIKE '%$search%'";
} else {
    $searchQuery = '';
}

// Calculate total records and pages
$totalQuery = "SELECT COUNT(*) FROM dishes $searchQuery"; // Count total rows
$totalResult = mysqli_query($conn, $totalQuery);
$totalRows = mysqli_fetch_array($totalResult)[0]; // Fetch total rows
$totalPages = ceil($totalRows / $limit); // Total pages calculation

// Fetch dishes from the database with sorting
$query = "SELECT * FROM dishes $searchQuery ORDER BY price $order LIMIT $limit OFFSET $offset";
$results = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dish List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to the new CSS file -->
</head>
<body>

    <div class="container">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Dish Listing</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="logout.php">Logout</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addDishModal">Add New Dish</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Search Form -->
        <div class="search-bar">
            <form method="GET">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Search dishes..." value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-outline-secondary btn-custom" type="submit">Search</button>
                </div>
            </form>
        </div>

        <!-- Table of Dishes -->
<div class="table-wrapper">
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Price
                    <div class="sort-buttons" style="display: inline-block; margin-left: 10px;">
                        <a href="?page=<?php echo $page; ?>&limit=<?php echo $limit; ?>&search=<?php echo htmlspecialchars($search); ?>&order=<?php echo ($order == 'ASC') ? 'DESC' : 'ASC'; ?>" class="sort-button">
                            <?php echo ($order == 'ASC') ? '↓' : '↑'; ?>
                        </a>
                    </div>
                </th>
                <th>Quantity</th> <!-- Added Quantity column -->
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($results)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td> <!-- Display the quantity -->
                    <td>
                        <button type="button" class="btn btn-warning btn-sm btn-custom edit-dish-button" data-id="<?php echo $row['id']; ?>">
                            Edit
                        </button>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm btn-custom" onclick="return confirm('Are you sure you want to delete this dish?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>&search=<?php echo htmlspecialchars($search); ?>&order=<?php echo $order; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

   <!-- Modal for Adding Dish -->
<div class="modal fade" id="addDishModal" tabindex="-1" aria-labelledby="addDishModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDishModalLabel">Add New Dish</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addDishForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Dish Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select" required>
                            <option value="" disabled selected>Select Category</option>
                            <option value="Appetizer">Appetizer</option>
                            <option value="Main Course">Main Course</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Beverage">Beverage</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" name="price" id="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity Available</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Add Dish</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Dish Modal -->
<div class="modal fade" id="editDishModal" tabindex="-1" aria-labelledby="editDishModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDishModalLabel">Edit Dish</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDishForm">
                    <input type="hidden" id="edit-dish-id" name="id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Dish Name</label>
                        <input type="text" id="edit-name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-description" class="form-label">Description</label>
                        <textarea id="edit-description" name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-price" class="form-label">Price</label>
                        <input type="number" id="edit-price" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-category" class="form-label">Category</label>
                        <input type="text" id="edit-category" name="category" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-quantity" class="form-label">Quantity</label>
                        <input type="number" id="edit-quantity" name="quantity" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





    <!-- BOI NANDITO JS -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        // Handle form submission for adding a new dish
        $('#addDishForm').on('submit', function (e) {
            e.preventDefault();  // Prevent the default form submission

            var formData = $(this).serialize();  // Serialize form data for submission

            // Send the data to create.php to insert into the database
            $.ajax({
                url: 'create.php',  // The script to insert the new dish
                type: 'POST',
                data: formData,
                success: function (response) {
                    const res = JSON.parse(response);

                    if (res.success) {
                        alert('Dish added successfully!');
                        $('#addDishModal').modal('hide');  // Close the modal
                        location.reload();  // Refresh the page to show the new dish
                    } else {
                        alert('Failed to add dish: ' + res.error);
                    }
                },
                error: function () {
                    alert('An error occurred while adding the dish.');
                },
            });
        });
    });
</script>


    <!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        // Open the modal and populate fields with dish data
        $('.edit-dish-button').on('click', function () {
            const dishId = $(this).data('id');

            // Fetch dish data via AJAX
            $.ajax({
                url: 'edit.php',
                type: 'GET',
                data: { id: dishId },
                success: function (response) {
                    const dish = JSON.parse(response);

                    if (dish.success) {
                        $('#edit-dish-id').val(dish.data.id);
                        $('#edit-name').val(dish.data.name);
                        $('#edit-description').val(dish.data.description);
                        $('#edit-price').val(dish.data.price);
                        $('#edit-category').val(dish.data.category);
                        $('#edit-quantity').val(dish.data.quantity);

                        // Show the modal
                        $('#editDishModal').modal('show');
                    } else {
                        alert('Failed to fetch dish details.');
                    }
                },
                error: function () {
                    alert('An error occurred while fetching the dish details.');
                },
            });
        });

        // Handle form submission for editing dish
        $('#editDishForm').on('submit', function (e) {
            e.preventDefault();

            const formData = $(this).serialize();

            // Send updated data via AJAX
            $.ajax({
                url: 'edit.php',
                type: 'POST',
                data: formData,
                success: function (response) {
                    const res = JSON.parse(response);

                    if (res.success) {
                        alert('Dish updated successfully!');
                        $('#editDishModal').modal('hide');
                        location.reload(); // Refresh the page to show updated data
                    } else {
                        alert('Failed to update dish: ' + res.error);
                    }
                },
                error: function () {
                    alert('An error occurred while updating the dish.');
                },
            });
        });
    });
</script>
    





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

    </script>
    <footer class="footer">
    &copy; 2024 Dish Listing. All Rights Reserved ♥ 
    <a href="https://github.com/YlunoZup" target="_blank" class="footer-link">YlunoZup</a>.
</footer>

</style>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
