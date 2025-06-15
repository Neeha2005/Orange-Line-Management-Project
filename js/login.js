document.addEventListener("DOMContentLoaded", function() {
    // Initialize the login page
    initLoginPage();
    
    // Set up all event listeners
    setupEventListeners();
    
    // Handle user form submission
    setupUserFormSubmission();
});

function initLoginPage() {
    // Initial screen setup
    hideAllScreens();
    showScreen("welcome-screen");
}

function setupEventListeners() {
    // Login flow navigation
    document.getElementById('mainLoginBtn')?.addEventListener('click', () => showScreen("role-screen"));
    document.getElementById('adminRoleBtn')?.addEventListener('click', () => showScreen("admin-login"));
    document.getElementById('userRoleBtn')?.addEventListener('click', () => showScreen("user-login"));
    
    // Back navigation
    document.getElementById('backToWelcomeBtn')?.addEventListener('click', () => showScreen("welcome-screen"));
    document.getElementById('backToRolesBtn1')?.addEventListener('click', () => showScreen("role-screen"));
    document.getElementById('backToRolesBtn2')?.addEventListener('click', () => showScreen("role-screen"));
    
    // Close button with improved redirection
    document.getElementById('closeLoginBtn')?.addEventListener('click', handleCloseButton);
}

function setupUserFormSubmission() {
    const userForm = document.getElementById("userForm");
    const messageContainer = document.createElement('div');
    messageContainer.id = 'loginMessage';
    userForm.appendChild(messageContainer);

    userForm.addEventListener("submit", async function(e) {
        e.preventDefault();
        
        const email = userForm.querySelector('input[name="email"]').value;
        const password = userForm.querySelector('input[name="password"]').value;
        const messageElement = document.getElementById('loginMessage');
        
        // Clear previous messages
        messageElement.textContent = '';
        messageElement.className = '';
        
        // Basic validation
        if (!email || !password) {
            showMessage('Please fill in all fields', 'error');
            return;
        }
        
        // Email format validation
        if (!validateEmail(email)) {
            showMessage('Please enter a valid email address', 'error');
            return;
        }

        try {
            const response = await fetch('api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ 
                    email: email,
                    password: password 
                })
            });

            const data = await response.json();

            if (data.success) {
                showMessage('Login successful! Redirecting...', 'success');
                // Store user data in session/local storage if needed
                if (data.userData) {
                    localStorage.setItem('user', JSON.stringify(data.userData));
                }
                // Redirect after short delay
                setTimeout(() => {
                    window.location.href = data.redirect || 'dashboard.html';
                }, 1000);
            } else {
                showMessage(data.message || 'Invalid credentials', 'error');
            }
        } catch (error) {
            console.error('Login error:', error);
            showMessage('An error occurred. Please try again.', 'error');
        }
    });
}

// Helper functions
function showScreen(screenId) {
    hideAllScreens();
    const screen = document.getElementById(screenId);
    if (screen) {
        screen.classList.remove("hidden");
    }
}

function hideAllScreens() {
    document.querySelectorAll(".login-screen").forEach(screen => {
        screen.classList.add("hidden");
    });
}

function showMessage(message, type) {
    const messageElement = document.getElementById('loginMessage');
    messageElement.textContent = message;
    messageElement.className = `message ${type}`;
    messageElement.style.display = 'block';
    
    // Hide message after 5 seconds
    setTimeout(() => {
        messageElement.style.display = 'none';
    }, 5000);
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function handleCloseButton() {
    if (canGoBack()) {
        window.history.back();
    } else {
        redirectToHome();
    }
}

function canGoBack() {
    return window.history.length > 1;
}

function redirectToHome() {
    const homePages = [
        'index.html',
        '../index.html',
        '/index.html',
        window.location.href.split('/').slice(0, 3).join('/')
    ];

    const accessiblePage = homePages.find(page => {
        try {
            return new URL(page, window.location.origin).href;
        } catch (e) {
            return false;
        }
    });

    window.location.href = accessiblePage || window.location.origin;
}