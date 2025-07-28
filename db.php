<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// db.php - Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'student_management';
try {
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        throw new Exception('Database connection failed: ' . $conn->connect_error);
    }
} catch (Exception $e) {
    die($e->getMessage());
}
?> 