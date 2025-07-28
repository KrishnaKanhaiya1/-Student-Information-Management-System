<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
$log = file_exists('student_log.txt') ? file_get_contents('student_log.txt') : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Logs</title>
    <style>body{font-family:Arial,sans-serif;}pre{background:#f9f9f9;padding:16px;border-radius:8px;max-width:600px;margin:40px auto;box-shadow:0 2px 8px #0002;}</style>
</head>
<body>
<h2 style="text-align:center;">Student Add/Delete Logs</h2>
<pre><?= htmlspecialchars($log) ?></pre>
<div style="text-align:center;"><a href="index.php">Back to Home</a></div>
</body>
</html> 