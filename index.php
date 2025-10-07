<?php
require 'functions.php';
require 'header.php';

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

$error = '';
if (isset($_POST['login'])) {
    $conn = connect_db();
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $username;
            header('Location: index.php');
            exit();
        }
    }
    $error = 'Invalid credentials!';
}
?>

<?php if (!isset($_SESSION['user'])): ?>
    <h2 style="text-align:center;">Login</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
        <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    </form>
<?php else: ?>
    <h2 style="text-align:center;">Welcome, <?= htmlspecialchars($_SESSION['user']) ?>!</h2>
    <p style="text-align:center;">Welcome to the Student Information Management System. Use the navigation links above to manage students.</p>
<?php endif; ?>

<form method="post" style="text-align:center; margin-top: 20px;">
    <label>Theme:
        <select name="theme" onchange="this.form.submit()">
            <option value="light" <?= $theme === 'light' ? 'selected' : '' ?>>Light</option>
            <option value="dark" <?= $theme === 'dark' ? 'selected' : '' ?>>Dark</option>
        </select>
    </label>
</form>

<?php require 'footer.php'; ?>