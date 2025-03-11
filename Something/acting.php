<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <!-- Enable responsive design -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Affiliate Program</title>

  <!-- Global stylesheet -->
  <link rel="stylesheet" href="styles.css">

  <style>
    /************************************************
     * Additional styles specific to this page
     ************************************************/

    /* Heading (below the top nav) */
    .affiliate-header {
      text-align: center;
      margin: 0 0 10px 0; 
      font-size: 18px;
      font-weight: bold;
    }

    /* Hero image container (standard block flow) */
    .affiliate-hero {
      width: 100%;
      text-align: center;
      margin: 0 auto;
    }

    /* Make the image full width (within .wrapper’s max-width) */
    .affiliate-hero img {
      width: 100%;
      height: auto;
      display: block;
      border: 0;
    }

    /* Table container overlaps the bottom of the image */
    .affiliate-table-container {
      position: relative;
      width: 90%;              /* Slightly narrower than .wrapper for a nice inset */
      margin: -40px auto 0;    /* Negative top margin to overlap the bottom of the image */
      z-index: 2;              /* Ensure it appears above the image */
    }

    .affiliate-table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      border-radius: 15px 15px 0 0; /* Rounded top corners */
      overflow: hidden;
      box-shadow: 0 2px 5px rgba(0,0,0,0.15); /* Slight shadow */
    }

    .affiliate-table th,
    .affiliate-table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
      color: #000;
      font-size: 14px;
    }

    .affiliate-table th {
      background: #fff;
      font-weight: bold;
    }

    .affiliate-table tr:nth-child(even) {
      background: #f2f2f2;
    }

    /* Responsive text adjustments for very small screens */
    @media (max-width: 375px) {
      .affiliate-header {
        font-size: 16px;
      }
      .affiliate-table th,
      .affiliate-table td {
        font-size: 12px;
        padding: 8px;
      }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Top Navigation Bar (from your styles.css) -->
    <div class="top-nav">
      <a href="home.php" class="back-button"><</a>
      <div class="title">Website Logo</div>
      <div class="hamburger-container">
        <div class="hamburger-menu" onclick="toggleMenu()">☰</div>
        <div id="dropdownMenu" class="dropdown-menu">
          <ul>
            <li>Profile</li>
            <li>Log Records</li>
            <li>Logout</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Main content container (with top/bottom padding from styles.css) -->
    <div class="container">
      <!-- Page heading -->
      <div class="affiliate-header">
        Affiliate Program
      </div>

      <!-- Image block -->
      <div class="affiliate-hero">
        <img 
          src="https://img.freepik.com/free-photo/multi-ethnic-group-three-businesspeople-meeting-modern-office-two-women-man-wearing-suit-looking-laptop-computer_1139-967.jpg" 
          alt="Business Team">
      </div>

      <!-- Overlapping table -->
      <div class="affiliate-table-container">
        <table class="affiliate-table">
          <tr>
            <th>Agent Tier</th>
            <th>Amount to promotion</th>
            <th>referral code</th>
            <th>Commission for first invitation</th>
          </tr>
          <tr>
            <td>1</td>
            <td>100000</td>
            <td>1</td>
            <td>50%</td>
          </tr>
          <tr>
            <td>2</td>
            <td>200000</td>
            <td>2</td>
            <td>70%</td>
          </tr>
          <tr>
            <td>3</td>
            <td>300000</td>
            <td>3</td>
            <td>100%</td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Bottom Navigation Bar (from your styles.css) -->
    <div class="bottom-nav">
      <div class="nav-item"><a href="home.php">Home</a></div>
      <div class="nav-item"><a href="logs.php">Logs</a></div>
      <div class="nav-item"><a href="tasks.php">Tasks</a></div>
      <div class="nav-item">Acting</div>
    </div>
  </div>

  <!-- JavaScript for the dropdown menu -->
  <script>
    function toggleMenu() {
      const dropdown = document.getElementById('dropdownMenu');
      dropdown.classList.toggle('show');
    }
  </script>
</body>
</html>
