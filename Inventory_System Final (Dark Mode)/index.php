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
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dish List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to your styles.css -->

    <style>
        body {
            background-image: url('css/chrstmas.gif'); /* Correct the image path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            padding: 20px;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8); /* White with transparency */
            border-radius: 15px; /* Smooth rounded edges */
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Optional: Add shadow for depth */
        }
    </style>
</head>

<div class="container mt-4" style="background-color: #18242B; border-radius: 15px; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.5); padding: 20px;">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background-color: transparent; border-radius: 15px; padding: 10px 20px;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#" style="color: #84C9FB; font-weight: bold;">Dish Listing</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon" style="filter: invert(89%) sepia(31%) saturate(257%) hue-rotate(179deg) brightness(97%) contrast(97%);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="logout.php" style="border: 2px solid #FF5733; border-radius: 15px; padding: 8px 15px; color: #84C9FB; transition: all 0.3s ease;">
                            Logout
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addDishModal" style="border: 2px solid #28a745; border-radius: 15px; padding: 8px 15px; color: #84C9FB; transition: all 0.3s ease;">
                            Add New Dish
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Search Form -->
<div class="search-bar mt-4" style="padding: 20px; background-color: rgba(255, 255, 255, 0.05); border-radius: 10px;">
    <form method="GET">
        <div class="input-group mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search dishes..." value="<?php echo htmlspecialchars($search); ?>" style="border-radius: 10px 0 0 10px; background-color: #18242B; color: #84C9FB; border: 1px solid #84C9FB; placeholder-color: #A6C8FF;">
            <button class="btn btn-outline-secondary btn-custom" type="submit" style="border-radius: 0 10px 10px 0; background-color: #84C9FB; color: #18242B; border: none;">Search</button>
        </div>
    </form>
</div>

<!-- Entries per Page -->
<div class="entries-per-page mt-2" style="display: flex; justify-content: flex-end; align-items: center;">
    <form method="GET" style="margin-right: 20px;">
        <label for="limit" style="color: #84C9FB; margin-right: 10px;">Entries per Page:</label>
        <select name="limit" id="limit" class="form-select" onchange="this.form.submit()" style="width: auto; display: inline-block; background-color: #18242B; color: #84C9FB; border: 1px solid #84C9FB; border-radius: 5px;">
            <option value="5" <?php echo ($limit == 5) ? 'selected' : ''; ?>>5</option>
            <option value="10" <?php echo ($limit == 10) ? 'selected' : ''; ?>>10</option>
            <option value="25" <?php echo ($limit == 25) ? 'selected' : ''; ?>>25</option>
            <option value="50" <?php echo ($limit == 50) ? 'selected' : ''; ?>>50</option>
        </select>
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
        <input type="hidden" name="page" value="<?php echo $page; ?>">
        <input type="hidden" name="order" value="<?php echo $order; ?>">
    </form>
</div>


    <!-- Table of Dishes -->
<div class="table-wrapper mt-4" style="background-color: rgba(255, 255, 255, 0.05); border-radius: 10px; padding: 20px;">
    <table class="table table-hover" style="color: #84C9FB; width: 100%; border-radius: 10px; overflow: hidden;">
        <thead style="background-color: #18242B; color: #84C9FB;">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Price
                    <div class="sort-buttons" style="display: inline-block; margin-left: 10px;">
                        <a href="?page=<?php echo $page; ?>&limit=<?php echo $limit; ?>&search=<?php echo htmlspecialchars($search); ?>&order=<?php echo ($order == 'ASC') ? 'DESC' : 'ASC'; ?>" class="sort-button" style="color: #84C9FB;">
                            <?php echo ($order == 'ASC') ? '↓' : '↑'; ?>
                        </a>
                    </div>
                </th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody style="background-color: #2a3a4b;">
            <?php while ($row = mysqli_fetch_assoc($results)): ?>
                <tr style="background-color: #2a3a4b; border-radius: 10px;">
                    <td style="color;"><?php echo htmlspecialchars($row['name']); ?></td> <!-- Lighter blue text with rounded edges -->
                    <td style="color:"><?php echo htmlspecialchars($row['description']); ?></td> <!-- Lighter blue text with rounded edges -->
                    <td style="color:"><?php echo htmlspecialchars($row['category']); ?></td> <!-- Lighter blue text with rounded edges -->
                    <td style="color:">₱<?php echo number_format($row['price'], 2); ?></td> <!-- Lighter blue text with rounded edges -->
                    <td style="color:"><?php echo htmlspecialchars($row['quantity']); ?></td> <!-- Lighter blue text with rounded edges -->
                    <td>
                        <button type="button" class="btn btn-warning btn-sm btn-custom edit-dish-button" data-id="<?php echo $row['id']; ?>" style="color: #18242B; border-radius: 5px;">
                            Edit
                        </button>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm btn-custom" onclick="return confirm('Are you sure you want to delete this dish?');" style="color: #18242B; border-radius: 5px;">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
        <!-- Pagination -->
        <nav>
    <ul class="pagination justify-content-center" style="display: flex; gap: 5px;">
        <!-- Single toggle button for First/Last -->
        <li class="page-item" style="border-radius: 8px; transition: all 0.3s ease;">
            <a class="page-link" id="first-last-toggle" href="javascript:void(0)" style="border-radius: 8px; padding: 10px 20px; background-color: #007bff; color: white; transition: all 0.3s ease;">
                <?php echo ($page == $totalPages) ? 'First' : 'Last'; ?>
            </a>
        </li>

        <!-- Pagination Number Buttons -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item" style="border-radius: 8px;">
                <a class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>" 
                   href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>&search=<?php echo htmlspecialchars($search); ?>&order=<?php echo $order; ?>" 
                   style="border-radius: 8px; padding: 10px 15px; background-color: <?php echo ($i == $page) ? '#007bff' : '#f0f0f0'; ?>; color: <?php echo ($i == $page) ? 'white' : '#007bff'; ?>; transition: all 0.3s ease;">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>

<!-- JavaScript to handle First/Last button toggling -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('first-last-toggle');
    const currentPage = <?php echo $page; ?>;
    const totalPages = <?php echo $totalPages; ?>;

    // Update the href and text of the button based on the current page
    toggleButton.addEventListener('click', function(event) {
        event.preventDefault();
        
        if (currentPage === totalPages) {
            window.location.href = "?page=1&limit=<?php echo $limit; ?>&search=<?php echo htmlspecialchars($search); ?>&order=<?php echo $order; ?>";
        } else {
            window.location.href = "?page=" + totalPages + "&limit=<?php echo $limit; ?>&search=<?php echo htmlspecialchars($search); ?>&order=<?php echo $order; ?>";
        }
    });
});
</script>

</script>
   <!-- Modal for Adding Dish -->
<div class="modal fade" id="addDishModal" tabindex="-1" aria-labelledby="addDishModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #18242B; color: #84C9FB; border-radius: 10px; border: 1px solid #84C9FB;">
            <div class="modal-header" style="border-bottom: 1px solid #84C9FB;">
                <h5 class="modal-title" id="addDishModalLabel">Add New Dish</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addDishForm">
                    <div class="mb-3">
                        <label for="name" class="form-label" style="color: #84C9FB;">Dish Name</label>
                        <input type="text" name="name" id="name" class="form-control" style="background-color: #2c3e50; color: #84C9FB; border: 1px solid #84C9FB;" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label" style="color: #84C9FB;">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" style="background-color: #2c3e50; color: #84C9FB; border: 1px solid #84C9FB;" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label" style="color: #84C9FB;">Category</label>
                        <select name="category" id="category" class="form-select" style="background-color: #2c3e50; color: #84C9FB; border: 1px solid #84C9FB;" required>
                            <option value="" disabled selected>Select Category</option>
                            <option value="Appetizer">Appetizer</option>
                            <option value="Main Course">Main Course</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Beverage">Beverage</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label" style="color: #84C9FB;">Price</label>
                        <input type="number" name="price" id="price" class="form-control" style="background-color: #2c3e50; color: #84C9FB; border: 1px solid #84C9FB;" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label" style="color: #84C9FB;">Quantity Available</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" style="background-color: #2c3e50; color: #84C9FB; border: 1px solid #84C9FB;" required>
                    </div>
                    <div class="d-grid">
                        <!-- Green Border Around Button -->
                        <button type="submit" class="btn btn-primary" style="border: 2px solid #28a745; border-radius: 8px; background-color: #28a745; color: white; transition: all 0.3s ease;">
                            Add Dish
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Dish Modal -->
<div class="modal fade" id="editDishModal" tabindex="-1" aria-labelledby="editDishModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #18242B; color: #84C9FB; border-radius: 10px; border: 1px solid #84C9FB;">
            <div class="modal-header" style="border-bottom: 1px solid #84C9FB;">
                <h5 class="modal-title" id="editDishModalLabel">Edit Dish</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDishForm">
                    <input type="hidden" id="edit-dish-id" name="id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label" style="color: #84C9FB;">Dish Name</label>
                        <input type="text" id="edit-name" name="name" class="form-control" style="background-color: #2c3e50; color: #84C9FB; border: 1px solid #84C9FB;" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-description" class="form-label" style="color: #84C9FB;">Description</label>
                        <textarea id="edit-description" name="description" class="form-control" rows="4" style="background-color: #2c3e50; color: #84C9FB; border: 1px solid #84C9FB;" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit-price" class="form-label" style="color: #84C9FB;">Price</label>
                        <input type="number" id="edit-price" name="price" class="form-control" style="background-color: #2c3e50; color: #84C9FB; border: 1px solid #84C9FB;" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-category" class="form-label" style="color: #84C9FB;">Category</label>
                        <input type="text" id="edit-category" name="category" class="form-control" style="background-color: #2c3e50; color: #84C9FB; border: 1px solid #84C9FB;" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-quantity" class="form-label" style="color: #84C9FB;">Quantity</label>
                        <input type="number" id="edit-quantity" name="quantity" class="form-control" style="background-color: #2c3e50; color: #84C9FB; border: 1px solid #84C9FB;" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" style="border: 2px solid #28a745; border-radius: 8px; background-color: #28a745; color: white; transition: all 0.3s ease;">
                            Save Changes
                        </button>
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
                        location.reload(); 
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Footer styling */
    footer.footer {
         background-color: #333; /* Dark background */
        color: white; /* White text */
        text-align: center; /* Center the text */
        padding: 10px 0; /* Reduced padding to bring it closer */
        position: relative;
        width: 100%;
        bottom: 0;
        font-size: 14px; /* Adjust font size */
        margin-top: -70px; /* Raised the footer by increasing margin-top */
        border-radius: 15px; /* Smooth edges with rounded corners */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional: Adds a subtle shadow for depth */
        }

    .footer-link {
        color: #007bff; /* Distinct link color */
        font-weight: bold; /* Make the link stand out */
        text-decoration: none; /* Remove underline */
        transition: color 0.3s ease, text-shadow 0.3s ease; /* Smooth hover effect */
        }

    .footer-link:hover {
        color: #0056b3; /* Darker shade on hover */
        text-shadow: 0px 0px 5px rgba(0, 123, 255, 0.6); /* Glowing effect */
        text-decoration: underline; /* Optional underline on hover */
        }

        /* Optional: Sticky footer for fixed positioning */
    html, body {
        height: 100%;
        margin: 0;
        }

    .content {
        min-height: 100%;
        padding-bottom: 60px; /* Adjust to footer height */
        }
    </style>
        </head>
    <body>
    <div class="content">
        <!-- Your page content, including pagination, goes here -->
        <div class="pagination">
            <!-- Your pagination code here -->
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        &copy; 2024 Dish Listing. All Rights Reserved ♥ 
        <a href="https://github.com/YlunoZup" target="_blank" class="footer-link">YlunoZup</a>.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


