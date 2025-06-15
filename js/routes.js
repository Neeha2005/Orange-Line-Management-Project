// Wait for DOM to be fully loaded
document.addEventListener("DOMContentLoaded", () => {
  // Handle Login Button
  const loginBtn = document.querySelector(".login-btn");
  if (loginBtn) {
    loginBtn.addEventListener("click", () => {
      // Redirect to login page
      window.location.href = 'login.html';
    });
  }

  // Handle Register Button
  const registerBtn = document.querySelector(".register-btn");
  if (registerBtn) {
    registerBtn.addEventListener("click", () => {
      // Replace this with your register page redirect
      alert("Register button clicked - will redirect to register page");
      // window.location.href = 'register.html'; // Uncomment when ready
    });
  }
});