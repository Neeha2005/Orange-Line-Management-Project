<?php
session_start();
$db = new PDO('sqlite:' . __DIR__ . '/data/orange_line_project.db');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

// Get and sanitize inputs
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'] ?? '';

// Basic validation
if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = 'Email and password are required.';
    header("Location: login.php");
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT user_id, username, email, password, role FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['login_error'] = 'Email not registered.';
        header("Location: login.php");
        exit;
    }

    if (!password_verify($password, $user['password'])) {
        $_SESSION['login_error'] = 'Incorrect password.';
        header("Location: login.php");
        exit;
    }

    // Login success
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['username'] = $user['username'];

    // Update last login
    $updateStmt = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE user_id = :user_id");
    $updateStmt->bindParam(':user_id', $user['user_id'], PDO::PARAM_INT);
    $updateStmt->execute();

    // Redirect based on role
    if ($user['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: user_dashboard.php");
    }
    exit;

} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    $_SESSION['login_error'] = 'A system error occurred. Try again later.';
    header("Location: login.php");
    exit;
}
