<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// Handle theme color preference
if (isset($_POST['theme'])) {
    setcookie('theme', $_POST['theme'], time() + (86400 * 30), "/");
    $_COOKIE['theme'] = $_POST['theme'];
}
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';

// Simple login logic (username: admin, password: admin123)
$error = '';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user'] = $username;
        header('Location: index.php');
        exit();
    } else {
        $error = 'Invalid credentials!';
    }
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Management System - Home</title>
    <style>
        body { background: <?= $theme === 'dark' ? '#222' : '#fff' ?>; color: <?= $theme === 'dark' ? '#fff' : '#222' ?>; font-family: Arial, sans-serif; }
        .container { max-width: 400px; margin: 60px auto; padding: 24px; border-radius: 8px; background: <?= $theme === 'dark' ? '#333' : '#f9f9f9' ?>; box-shadow: 0 2px 8px #0002; }
        input, select { width: 100%; padding: 8px; margin: 8px 0; }
        .error { color: red; }
        .nav { margin: 16px 0; }
        .nav a { margin-right: 12px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Student Information Management System</h2>
    <form method="post">
        <label>Theme:
            <select name="theme" onchange="this.form.submit()">
                <option value="light" <?= $theme==='light'?'selected':'' ?>>Light</option>
                <option value="dark" <?= $theme==='dark'?'selected':'' ?>>Dark</option>
            </select>
        </label>
    </form>
    <?php if (!isset($_SESSION['user'])): ?>
        <h3>Login</h3>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <?php else: ?>
        <div>Welcome, <b><?= htmlspecialchars($_SESSION['user']) ?></b>!</div>
        <div class="nav">
            <a href="add_student.php">Add Student</a>
            <a href="view_students.php">View Students</a>
            <a href="search_student.php">Search Student</a>
            <a href="logs.php">View Logs</a>
            <a href="index.php?logout=1">Logout</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html> 