<?php
require 'functions.php';
$conn = connect_db();
require 'header.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE name LIKE ? OR email LIKE ?");
    $like_term = "%{$search}%";
    $stmt->bind_param('ss', $like_term, $like_term);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<h2 style="text-align:center;">Search Student</h2>
<form method="get">
    <input type="text" name="q" placeholder="Enter name or email" value="<?= htmlspecialchars($search) ?>" required>
    <button type="submit">Search</button>
</form>

<?php if (!empty($search)): ?>
    <h3 style="text-align:center;">Results for "<?= htmlspecialchars($search) ?>"</h3>
    <?php if ($results->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>DOB</th>
                <th>Course</th>
                <th>Grade</th>
                <th>Profile Pic</th>
            </tr>
            <?php while($row = $results->fetch_assoc()): ?>
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
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">No students found.</p>
    <?php endif; ?>
<?php endif; ?>

<?php require 'footer.php'; ?>