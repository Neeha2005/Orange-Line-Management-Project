<?php
session_start();
require_once 'data/db_connect.php'; // This must define $conn (not $pdo)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $username = trim($_POST['full_name']); // assuming full_name is used as username
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: Register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address.";
        header("Location: Register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: Register.php");
        exit();
    }

    try {
        // Use $conn instead of $pdo
        // Check for duplicate username or email
        $checkStmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $checkStmt->execute([$username, $email]);

        if ($checkStmt->fetch()) {
            $_SESSION['error'] = "Username or email already exists.";
            header("Location: Register.php");
            exit();
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $insertStmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'user')");
        $insertStmt->execute([$username, $hashed_password, $email]);

        $_SESSION['success'] = "Registration successful. Please login.";
        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: Register.php");
        exit();
    }
} else {
    // If not POST, redirect
    header("Location: Register.php");
    exit();
}
