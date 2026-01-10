<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'crockery_store'); 
define('DB_USER', 'root');
define('DB_PASS', '');

// Database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Site settings
define('SITE_NAME', 'ElegantWare');
define('SITE_URL', 'http://localhost/ElegantWare/User/Customer/');

define('BASE_PATH', __DIR__ . '/../'); 
define('MODEL_PATH', __DIR__ . '/'); 
define('CONTROLLER_PATH', BASE_PATH . 'Controller/');
define('VIEW_PATH', BASE_PATH . 'View/');

// Web URLs 
define('WEB_ROOT', '/ElegantWare/User/Customer/');
define('ASSETS_URL', WEB_ROOT . 'View/');
?>