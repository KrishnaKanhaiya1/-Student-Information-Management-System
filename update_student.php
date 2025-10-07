<?php
require 'functions.php';
$conn = connect_db();
require 'header.php';

if (!isset($_GET['id'])) {
    header('Location: view_students.php');
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    header('Location: view_students.php');
    exit();
}

$error = $success = '';
$name = $student['name'];
$email = $student['email'];
$dob = $student['dob'];
$course = $student['course'];
$grade = $student['grade'];
$profile_pic = $student['profile_pic'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $dob = $_POST['dob'];
    $course = trim($_POST['course']);
    $grade = trim($_POST['grade']);

    if (empty($name) || empty($email) || empty($dob) || empty($course)) {
        $error = 'Please fill all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        $upload_result = handle_profile_pic_upload('profile_pic', $profile_pic);
        if (isset($upload_result['error'])) {
            $error = $upload_result['error'];
        } else {
            $profile_pic = $upload_result['filepath'];
            $stmt = $conn->prepare("UPDATE students SET name=?, email=?, dob=?, course=?, grade=?, profile_pic=? WHERE id=?");
            $stmt->bind_param('ssssssi', $name, $email, $dob, $course, $grade, $profile_pic, $id);

            if ($stmt->execute()) {
                log_action("Updated student: $name ($email)");
                $success = 'Student updated successfully!';
            } else {
                $error = 'Error updating student: ' . $stmt->error;
            }
        }
    }
}
?>

<h2 style="text-align:center;">Update Student</h2>
<form method="post" enctype="multipart/form-data">
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>

    <input type="text" name="name" placeholder="Name" value="<?= htmlspecialchars($name) ?>" required>
    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
    <input type="date" name="dob" value="<?= htmlspecialchars($dob) ?>" required>
    <input type="text" name="course" placeholder="Course" value="<?= htmlspecialchars($course) ?>" required>
    <input type="text" name="grade" placeholder="Grade (optional)" value="<?= htmlspecialchars($grade) ?>">

    <?php if ($profile_pic && file_exists($profile_pic)): ?>
        <img src="<?= htmlspecialchars($profile_pic) ?>" style="display:block; margin-bottom:10px;">
    <?php endif; ?>

    <label>Change Profile Picture: <input type="file" name="profile_pic" accept="image/*"></label>
    <button type="submit">Update Student</button>
</form>

<?php require 'footer.php'; ?>