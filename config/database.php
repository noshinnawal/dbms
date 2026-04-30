<?php
/**
 * Database Configuration
 * Standard XAMPP defaults: localhost, root, no password
 */

$host = 'localhost';
$db   = 'university_sms';
$user = 'root';
$pass = ''; // Default XAMPP password is empty
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // For development, display the error. In production, log it.
     die("Database connection failed. Please ensure MySQL is running in XAMPP and the database 'university_sms' exists.<br>Error: " . $e->getMessage());
}
?>
