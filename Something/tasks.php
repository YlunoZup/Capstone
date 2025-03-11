<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=375, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Tasks</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
    /* TASK ITEMS */
    .task-items {
        margin-bottom: 20px;
    }

    .task-item {
        display: flex;
        align-items: center;
        background-color: #fff;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .task-item img {
        width: 50px;
        height: 50px;
        margin-right: 10px;
        background-color: #ddd;
        border-radius: 5px;
    }

    .task-details {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .task-title {
        font-weight: bold;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .task-desc {
        font-size: 12px;
        color: #666;
    }

    /* START TASK SECTION */
    .start-task-button {
        display: inline-block;
        font-size: 18px;
        text-align: center;
        background-color: #000;
        color: #fff;
        padding: 12px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
        width: 100%;
    }

    .start-task-button:hover {
        background-color: #222;
    }

    /* WALLET BUTTON STYLES */
    .wallet-container {
        display: flex;
        justify-content: center;
        margin-bottom: 10px;
    }

    .wallet-button {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .icon-wrapper {
        width: 25px;
        height: 25px;
        background-color: #ddd;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 14px;
        color: #000;
        margin-right: 5px;
    }

    .wallet-text {
        font-size: 14px;
        color: #000;
    }

    .wallet-button:hover .icon-wrapper {
        background-color: #ccc;
    }

    /* MODAL STYLES */
    .modal {
        display: none;
        position: fixed; /* Changed to fixed to cover the entire viewport */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Grey out the entire screen */
        justify-content: center;
        align-items: center;
        z-index: 1000; /* Above all other elements */
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        width: 90%;
        max-width: 500px;
        max-height: 90%; /* Ensure it doesn't overflow the screen */
        overflow-y: auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .modal-title {
        font-weight: bold;
        font-size: 18px;
        text-transform: uppercase;
    }

    .close-button {
        cursor: pointer;
        font-size: 24px;
        color: #000;
    }

    .modal-desc {
        font-size: 12px;
        color: #666;
        margin-bottom: 20px;
    }

    .modal-footer {
        text-align: center;
    }
</style>
<body>

  <div class="wrapper">
    <!-- Top Navigation Bar -->
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

    <!-- Main Content -->
    <div class="container">
      <div class="wallet-container">
        <button class="wallet-button">
          <div class="icon-wrapper">
            <i class="fa-solid fa-wallet"></i>
          </div>
          <span class="wallet-text">wallet</span>
        </button>
      </div>
      <h2>Lorem ipsum dolor sit amet</h2>
      <p>Lorem ipsum</p>
      <p>Quo quisquam eligendi eum sunt deserunt vel exercitationem distinctio sit autem architecto.</p>

      <!-- List of Items -->
      <div class="task-items">
        <div class="task-item">
          <img src="placeholder.png" alt="Item Placeholder">
          <div class="task-details">
            <div class="task-title">Quo consequatur ullam et excepturi</div>
            <div class="task-desc">enim qui quos tempora eum nisi doloribus non molestiae doloribus.</div>
          </div>
        </div>

        <div class="task-item">
          <img src="placeholder.png" alt="Item Placeholder">
          <div class="task-details">
            <div class="task-title">Quo consequatur ullam et excepturi</div>
            <div class="task-desc">enim qui quos tempora eum nisi doloribus non molestiae doloribus.</div>
          </div>
        </div>

        <div class="task-item">
          <img src="placeholder.png" alt="Item Placeholder">
          <div class="task-details">
            <div class="task-title">Quo consequatur ullam et excepturi</div>
            <div class="task-desc">enim qui quos tempora eum nisi doloribus non molestiae doloribus.</div>
          </div>
        </div>

        <div class="task-item">
          <img src="placeholder.png" alt="Item Placeholder">
          <div class="task-details">
            <div class="task-title">Quo consequatur ullam et excepturi</div>
            <div class="task-desc">enim qui quos tempora eum nisi doloribus non molestiae doloribus.</div>
          </div>
        </div>
      </div>

      <!-- "Start Task" Button -->
      <div class="start-card">
        <a href="#" class="start-task-button">Complete a Task</a>
      </div>
    </div>

    <!-- Bottom Navigation Bar -->
    <div class="bottom-nav">
      <div class="nav-item"><a href="home.php">Home</a></div>
      <div class="nav-item"><a href="logs.php">Logs</a></div>
      <div class="nav-item">Tasks</div>
      <div class="nav-item"><a href="acting.php">Acting</a></div>
    </div>
  </div>

  <!-- Modal for Wallet (Moved outside .wrapper to cover the entire screen) -->
  <div class="modal" id="walletModal">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Review Your Favorite Apps</h2>
        <span class="close-button">×</span>
      </div>
      <div class="modal-desc">
        Share your feedback on the apps you use daily and help others discover great tools.
      </div>
      <div class="task-items">
        <div class="task-item">
          <img src="placeholder.png" alt="Item Placeholder">
          <div class="task-details">
            <div class="task-title">Review App Store Ratings</div>
            <div class="task-desc">Help improve app quality with your insights and suggestions.</div>
          </div>
        </div>
        <div class="task-item">
          <img src="placeholder.png" alt="Item Placeholder">
          <div class="task-details">
            <div class="task-title">Write a Review for Productivity Apps</div>
            <div class="task-desc">Help improve app quality with your insights and suggestions.</div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a href="#" class="start-task-button">Submit Your Review</a>
      </div>
    </div>
  </div>

  <script>
    function toggleMenu() {
      const dropdown = document.getElementById('dropdownMenu');
      dropdown.classList.toggle('show');
    }

    const walletButton = document.querySelector('.wallet-button');
    const modal = document.getElementById('walletModal');
    const closeButton = document.querySelector('.close-button');

    walletButton.addEventListener('click', () => {
      modal.style.display = 'flex';
    });

    closeButton.addEventListener('click', () => {
      modal.style.display = 'none';
    });

    // Close modal when clicking outside
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  </script>

</body>
</html>