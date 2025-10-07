<?php
require 'functions.php';
$conn = connect_db();
require 'header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // First, get student info for logging and file deletion
    $stmt = $conn->prepare("SELECT name, email, profile_pic FROM students WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($student = $result->fetch_assoc()) {
        // Delete profile picture if it exists
        if (!empty($student['profile_pic']) && file_exists($student['profile_pic'])) {
            unlink($student['profile_pic']);
        }

        // Delete student record
        $delete_stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $delete_stmt->bind_param('i', $id);
        if ($delete_stmt->execute()) {
            log_action("Deleted student: {$student['name']} ({$student['email']})");
        }
    }

    header('Location: view_students.php');
    exit();
}

$students = $conn->query("SELECT * FROM students ORDER BY id DESC");
?>

<h2 style="text-align:center;">All Students</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>DOB</th>
        <th>Course</th>
        <th>Grade</th>
        <th>Profile Pic</th>
        <th>Actions</th>
    </tr>
    <?php while($row = $students->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['dob']) ?></td>
        <td><?= htmlspecialchars($row['course']) ?></td>
        <td><?= htmlspecialchars($row['grade']) ?></td>
        <td>
            <?php if ($row['profile_pic'] && file_exists($row['profile_pic'])): ?>
                <img src="<?= htmlspecialchars($row['profile_pic']) ?>">
            <?php endif; ?>
        </td>
        <td>
            <a href="update_student.php?id=<?= $row['id'] ?>">Update</a> |
            <a href="view_students.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php require 'footer.php'; ?>