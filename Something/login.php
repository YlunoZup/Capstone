<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="login.css">
  <script>
    function formatMobileNumber() {
      let mobileInput = document.getElementById("mobile");
      let number = mobileInput.value.replace(/\D/g, ''); // Remove non-numeric characters

      if (number.startsWith("92")) { // Pakistan
        if (number.length > 2) {
          number = "+92 " + number.substring(2, 5) + " " + number.substring(5, 12);
        }
      } else if (number.startsWith("1")) { // United States
        if (number.length > 1) {
          number = "+1 (" + number.substring(1, 4) + ") " + number.substring(4, 7) + "-" + number.substring(7, 11);
        }
      }
      
      mobileInput.value = number.trim(); // Apply formatted number
    }
  </script>
</head>
<body>
  <div class="wrapper">
    <div class="container">
      <div class="card">
        <h2>Login</h2>
        <form action="home.php" method="POST">
          <div class="input-group">
            <label for="mobile">Mobile Number</label>
            <input list="countryCodes" id="mobile" name="mobile" placeholder="Select country code & enter number" oninput="formatMobileNumber()" required>
            <datalist id="countryCodes">
              <option value="+92">Pakistan (+92)</option>
              <option value="+1">United States (+1)</option>
            </datalist>
          </div>
          <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
          </div>
          <button type="submit" class="btn">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
      </div>
    </div>
  </div>
</body>
</html>
