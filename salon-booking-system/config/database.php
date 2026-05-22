<?php
// Database settings for XAMPP/WAMP. Edit these values if your local setup differs.
$host = 'localhost';
$dbname = 'salondb';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log('Database connection error: ' . $e->getMessage());
    die('Database connection failed. Please check config/database.php and make sure MySQL is running.');
}
