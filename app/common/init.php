<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DB_HOST', 'localhost');      // Change this to your database host
define('DB_NAME', 'upay_db');  // Change this to your database name
define('DB_USER', 'root');  // Change this to your database username
define('DB_PASS', '');  // Change this to your database password

// Database connection function
function getDBConnection() {
    static $db = null;
    
    if ($db === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $db = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $db;
}

// Autoload function for including class files dynamically
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/../models/' . $class . '.php'; // Assuming models are in the `models` folder
    if (file_exists($file)) {
        require_once $file;
    }
});

// Authentication helper function
function isAuthenticated() {
    return isset($_SESSION['user_id']);
}

// Get the currently authenticated user ID
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Redirect helper function
function redirect($url) {
    header("Location: $url");
    exit;
}

?>
