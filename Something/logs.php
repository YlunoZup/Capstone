<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Lock the viewport for a mobile-like view -->
    <meta name="viewport" content="width=375, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Logs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Sample styling for the log card */
        .log-card {
            background-color: #fff;
            padding: 1em;
            margin: 1em 0;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .log-card p {
            margin: 0.5em 0;
        }

        .log-card strong {
            color: #333;
        }

        /* Sort Container */
        .sort-container {
            width: 100%;
            background-color: #fff;
        }

        /* Sort Navigation Bar */
        .sort-nav {
            display: flex;
            justify-content: space-around; /* Distributes items evenly */
            background-color: #000; /* Black background */
            color: #fff; /* White text */
            padding: 0; /* Remove padding to eliminate gaps */
            margin: 0; /* Remove margin to eliminate gaps */
            width: 100%; /* Ensure full width */
        }

        /* Sort Items */
        .sort-item {
            padding: 10px 20px;
            cursor: pointer;
            text-align: center;
            flex: 1; /* Equal width for each item */
        }

        .sort-item.active {
            background-color: #333; /* Highlight active item */
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Top Navigation Bar -->
        <div class="top-nav">
            <a href="home.php" class="back-button"><</a>
            <div class="title">Website logo</div>
            <div class="hamburger-container">
                <div class="hamburger-menu" onclick="toggleMenu()">☰</div>
                <div id="dropdownMenu" class="dropdown-menu">
                    <ul>
                        <li>Profile</li>
                        <li>Log Record</li>
                        <li>Logout</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content Container -->
        <div class="container">
            <h2>Reviews</h2>
            
            <!-- Sorting Navigation Bar -->
            <div class="sort-container">
                <div class="sort-nav">
                    <div class="sort-item active" onclick="filterLogs('all')">All</div>
                    <div class="sort-item" onclick="filterLogs('processing')">Processing</div>
                    <div class="sort-item" onclick="filterLogs('completed')">Completed</div>
                </div>
            </div>
            
            <!-- Sample Log Card -->
            <div class="log-card">
                <p><strong>Review Name:</strong> [Placeholder]</p>
                <p><strong>Review Number:</strong> [Placeholder]</p>
                <p><strong>Review Submitted:</strong> [Placeholder]</p>
                <p><strong>App reviewed:</strong> [Placeholder]</p>
                <p><strong>Total Price:</strong> [Placeholder]</p>
            </div>
            <!-- Add more log entries as needed -->
        </div>
        
        <!-- Bottom Navigation Bar -->
        <div class="bottom-nav">
            <div class="nav-item"><a href="home.php">Home</a></div>
            <div class="nav-item">Logs</div>
            <div class="nav-item"><a href="tasks.php">Tasks</a></div>
            <div class="nav-item"><a href="acting.php">Acting</a></div>
        </div>
    </div>

    <script>
        function toggleMenu() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('show');
        }

        function filterLogs(status) {
            const cards = document.querySelectorAll('.log-card');
            cards.forEach(card => {
                if (status === 'all' || card.classList.contains(status)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            document.querySelectorAll('.sort-item').forEach(item => item.classList.remove('active'));
            document.querySelector(`[onclick="filterLogs('${status}')"]`).classList.add('active');
        }
    </script>
</body>
</html>