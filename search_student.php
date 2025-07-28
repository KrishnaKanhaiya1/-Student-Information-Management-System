<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
require 'db.php';
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];
if ($search) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ? OR email LIKE ?");
    $like = "%$search%";
    $stmt->bind_param('ss', $like, $like);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Student</title>
    <style>body{font-family:Arial,sans-serif;}form{max-width:400px;margin:40px auto;}table{border-collapse:collapse;width:90%;margin:30px auto;}th,td{border:1px solid #ccc;padding:8px;text-align:left;}th{background:#eee;}img{max-width:60px;max-height:60px;}</style>
</head>
<body>
<form method="get">
    <h2>Search Student</h2>
    <input type="text" name="q" placeholder="Enter name or email" value="<?= htmlspecialchars($search) ?>" required>
    <button type="submit">Search</button>
    <div style="margin-top:12px;"><a href="index.php">Back to Home</a></div>
</form>
<?php if ($search): ?>
    <h3 style="text-align:center;">Results for "<?= htmlspecialchars($search) ?>"</h3>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>DOB</th><th>Course</th><th>Grade</th><th>Profile Pic</th></tr>
        <?php while($row = $results->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= $row['dob'] ?></td>
            <td><?= htmlspecialchars($row['course']) ?></td>
            <td><?= htmlspecialchars($row['grade']) ?></td>
            <td><?php if ($row['profile_pic']): ?><img src="<?= $row['profile_pic'] ?>"><?php endif; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>
</body>
</html> 