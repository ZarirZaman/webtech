<?php
// Include config to start session and get database connection
require_once '../Model/config.php';

// Include auth functions
require_once '../Model/auth.php';

$message = '';
$message_type = '';

// Check if user is already logged in
if (isLoggedIn()) {
    redirect('index.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (loginUser($email, $password)) {
        redirect('index.php');
    } else {
        $message = 'Invalid email/username or password';
        $message_type = 'danger';
    }
}

// Check for registration success message
if (isset($_GET['registered']) && $_GET['registered'] == 'true') {
    $message = 'Registration successful! Please login with your credentials.';
    $message_type = 'success';
}

// Include the view
include '../View/html/login_view.php';
?>