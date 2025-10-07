<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Theme handling
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
if (isset($_POST['theme'])) {
    setcookie('theme', $_POST['theme'], time() + (86400 * 30), "/");
    $theme = $_POST['theme'];
}

// Check for user authentication
if (!isset($_SESSION['user']) && !in_array(basename($_SERVER['PHP_SELF']), ['index.php'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Management System</title>
    <style>
        body {
            background: <?= $theme === 'dark' ? '#222' : '#fff' ?>;
            color: <?= $theme === 'dark' ? '#fff' : '#222' ?>;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 24px;
            border-radius: 8px;
            background: <?= $theme === 'dark' ? '#333' : '#f9f9f9' ?>;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        form { max-width: 400px; margin: 20px auto; }
        input, select, button { width: 100%; padding: 10px; margin: 8px 0; border-radius: 4px; border: 1px solid #ccc; }
        button { background-color: #007BFF; color: white; cursor: pointer; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #eee; color: #333; }
        img { max-width: 60px; max-height: 60px; border-radius: 4px; }
        .nav { text-align: center; margin-bottom: 20px; }
        .nav a { margin: 0 15px; color: #007BFF; text-decoration: none; }
        .error { color: red; text-align: center; }
        .success { color: green; text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <div class="nav">
        <a href="index.php">Home</a>
        <?php if (isset($_SESSION['user'])): ?>
            <a href="add_student.php">Add Student</a>
            <a href="view_students.php">View Students</a>
            <a href="search_student.php">Search Student</a>
            <a href="logs.php">View Logs</a>
            <a href="index.php?logout=1">Logout</a>
        <?php endif; ?>
    </div>