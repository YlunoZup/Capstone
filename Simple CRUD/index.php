<?php
include "config.php"; // Database connection

// Retrieve search query, sorting parameters, and row limit
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'id'; // Default sorting column
$order = $_GET['order'] ?? 'asc'; // Default sorting order
$new_order = ($order === 'asc') ? 'desc' : 'asc'; // Toggle sorting order
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Default limit 10

// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Build the WHERE clause for search filtering
$whereClause = " WHERE 1=1 ";
$params = [];

if ($search) {
    $whereClause .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?) ";
    array_push($params, "%$search%", "%$search%", "%$search%");
}

// Get total count for pagination
$query = "SELECT COUNT(*) FROM clients" . $whereClause;
$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
}
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $totalItems);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
$totalPages = ceil($totalItems / $limit);

// Fetch clients with sorting
$query = "SELECT * FROM clients" . $whereClause . " ORDER BY $sort $order LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $query);
array_push($params, $limit, $offset);
mysqli_stmt_bind_param($stmt, str_repeat('s', count($params) - 2) . "ii", ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$clients = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        .sortable-header a {
            color: white !important;
            text-decoration: none;
        }
        .sortable-header a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Lendex Agency</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <h1 class="mb-3 text-center">Client List</h1>

        <!-- Search, Rows Dropdown, and Add Form -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
            <form method="GET" class="d-flex flex-grow-1 align-items-center">
                <input type="text" name="search" class="form-control me-2" style="max-width: 400px;" placeholder="Search clients..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary me-2">Search</button>
                <select name="limit" class="form-select me-2" style="width: auto;" onchange="this.form.submit()">
                    <?php foreach ([5, 10, 15, 20] as $option): ?>
                        <option value="<?php echo $option; ?>" <?php echo ($limit === $option) ? 'selected' : ''; ?>>Show <?php echo $option; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
            <a href="add.php?limit=<?php echo $limit; ?>" class="btn btn-success ms-3">Add Client</a>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <?php
                        $columns = [
                            'id' => 'ID',
                            'name' => 'Name',
                            'email' => 'Email',
                            'phone' => 'Phone',
                            'created_at' => 'Registered At'
                        ];
                        foreach ($columns as $key => $value) {
                            $arrow = ($sort === $key) ? (($order === 'asc') ? '⬆️' : '⬇️') : '';
                            echo "<th class='sortable-header'>
                                    <a href='?search=" . urlencode($search) . "&limit=$limit&sort=$key&order=$new_order'>$value $arrow</a>
                                  </th>";
                        }
                        ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                        <tr>
                            <td><?php echo $client['id']; ?></td>
                            <td><?php echo htmlspecialchars($client['name']); ?></td>
                            <td><?php echo htmlspecialchars($client['email']); ?></td>
                            <td><?php echo htmlspecialchars($client['phone']); ?></td>
                            <td><?php echo $client['created_at']; ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $client['id']; ?>&limit=<?php echo $limit; ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="delete.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

