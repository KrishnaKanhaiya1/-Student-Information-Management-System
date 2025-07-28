<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
require 'db.php';
// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $res = $conn->query("SELECT * FROM students WHERE id=$id");
    $stu = $res->fetch_assoc();
    if ($stu) {
        if ($stu['profile_pic'] && file_exists($stu['profile_pic'])) unlink($stu['profile_pic']);
        $conn->query("DELETE FROM students WHERE id=$id");
        file_put_contents('student_log.txt', date('Y-m-d H:i:s') . " - Deleted: {$stu['name']} ({$stu['email']})\n", FILE_APPEND);
    }
    header('Location: view_students.php');
    exit();
}
$students = $conn->query("SELECT * FROM students ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Students</title>
    <style>body{font-family:Arial,sans-serif;}table{border-collapse:collapse;width:90%;margin:30px auto;}th,td{border:1px solid #ccc;padding:8px;text-align:left;}th{background:#eee;}img{max-width:60px;max-height:60px;}</style>
</head>
<body>
<h2 style="text-align:center;">All Students</h2>
<table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>DOB</th><th>Course</th><th>Grade</th><th>Profile Pic</th><th>Actions</th></tr>
    <?php while($row = $students->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= $row['dob'] ?></td>
        <td><?= htmlspecialchars($row['course']) ?></td>
        <td><?= htmlspecialchars($row['grade']) ?></td>
        <td><?php if ($row['profile_pic']): ?><img src="<?= $row['profile_pic'] ?>"><?php endif; ?></td>
        <td>
            <a href="update_student.php?id=<?= $row['id'] ?>">Update</a> |
            <a href="view_students.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this student?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<div style="text-align:center;"><a href="index.php">Back to Home</a></div>
</body>
</html> 