<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <!-- Updated viewport to enable responsive design -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Mobile Homepage</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    /* CARD STYLES */
    .card {
        background: #fff;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .card img {
        width: 100%;
        border-radius: 10px;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Top Navigation Bar -->
    <div class="top-nav">
      <div class="title">Website logo</div>
      
      <!-- Hamburger Container (position: relative) -->
      <div class="hamburger-container">
        <!-- The clickable hamburger icon -->
        <div class="hamburger-menu" onclick="toggleMenu()">☰</div>
        
        <!-- Dropdown Menu (hidden by default) -->
        <div id="dropdownMenu" class="dropdown-menu">
          <ul>
            <li>Profile</li>
            <li>Log Records</li>
            <li>Logout</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="container">
      <div class="card">
        <img src="https://media.nature.com/lw767/magazine-assets/d41586-025-00754-4/d41586-025-00754-4_50728820.jpg?as=webp" alt="Gold Coins">
        <p>Lorem ipsum dolor sit amet.</p>
      </div>
      <div class="card">
        <img src="https://media.nature.com/w1248/magazine-assets/d41586-025-00605-2/d41586-025-00605-2_50709174.jpg?as=webp" alt="NZD/USD Exchange">
        <p>Et voluptatum sint eum sint sint nam</p>
      </div>
      <!-- Add more cards as needed -->
    </div>
    
    <!-- Bottom Navigation Bar -->
    <div class="bottom-nav">
      <div class="nav-item">Home</div>
      <div class="nav-item"><a href="logs.php">Logs</a></div>
      <div class="nav-item"><a href="tasks.php">Tasks</a></div>
      <div class="nav-item"><a href="acting.php">Acting</a></div>
    </div>
  </div>

  <!-- Simple JavaScript to toggle the dropdown menu -->
  <script>
    function toggleMenu() {
      const dropdown = document.getElementById('dropdownMenu');
      dropdown.classList.toggle('show');
    }
  </script>
</body>
</html>