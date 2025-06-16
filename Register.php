<?php
// Start session if needed (optional in login page, mostly needed after login)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orange Line - Register</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body id="registerPage">
  <div class="background-image"></div>

  <div class="login-container">
    <button class="close-login" id="closeRegisterBtn" aria-label="Close register">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </button>

    <!-- Welcome Screen -->
    <div class="login-screen" id="welcome-screen">
      <img src="css/images/logo (2).png" alt="Orange Line Logo" class="logo">
      <h2>Join Us Today</h2>
      <p>Create an account to access Orange Line services</p>
      <button id="registerStartBtn">Register</button>
    </div>

    <!-- Registration Form -->
    <div class="login-screen hidden" id="register-screen">
      <h2>User Registration</h2>
      <form id="registerForm" action="user_register.php" method="POST">
        <input type="text" name="full_name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="tel" name="phone" placeholder="Phone Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
      </form>
      <button class="back-btn" id="backToWelcomeBtn">Back</button>
    </div>
  </div>

  <script>
    const registerStartBtn = document.getElementById('registerStartBtn');
    const welcomeScreen = document.getElementById('welcome-screen');
    const registerScreen = document.getElementById('register-screen');
    const backToWelcomeBtn = document.getElementById('backToWelcomeBtn');

    registerStartBtn.addEventListener('click', () => {
      welcomeScreen.classList.add('hidden');
      registerScreen.classList.remove('hidden');
    });

    backToWelcomeBtn.addEventListener('click', () => {
      registerScreen.classList.add('hidden');
      welcomeScreen.classList.remove('hidden');
    });

    document.addEventListener("DOMContentLoaded", () => {
      const closeBtn = document.getElementById("closeRegisterBtn");
      if (closeBtn) {
        closeBtn.addEventListener("click", () => {
          window.location.href = "index.html"; // Or "index.html" if you use PHP for home
        });
      }
    });
  </script>
</body>
</html>
