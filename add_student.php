<?php
require 'functions.php';
$conn = connect_db();
require 'header.php';

$error = $success = '';
$name = $email = $dob = $course = $grade = '';

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
        $upload_result = handle_profile_pic_upload('profile_pic');
        if (isset($upload_result['error'])) {
            $error = $upload_result['error'];
        } else {
            $profile_pic = $upload_result['filepath'];
            $stmt = $conn->prepare("INSERT INTO students (name, email, dob, course, grade, profile_pic) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssss', $name, $email, $dob, $course, $grade, $profile_pic);

            if ($stmt->execute()) {
                log_action("Added student: $name ($email)");
                $success = 'Student added successfully!';
                // Clear form
                $name = $email = $dob = $course = $grade = '';
            } else {
                $error = 'Error adding student: ' . $stmt->error;
            }
        }
    }
}
?>

<h2 style="text-align:center;">Add Student</h2>
<form method="post" enctype="multipart/form-data">
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>

    <input type="text" name="name" placeholder="Name" value="<?= htmlspecialchars($name) ?>" required>
    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
    <input type="date" name="dob" value="<?= htmlspecialchars($dob) ?>" required>
    <input type="text" name="course" placeholder="Course" value="<?= htmlspecialchars($course) ?>" required>
    <input type="text" name="grade" placeholder="Grade (optional)" value="<?= htmlspecialchars($grade) ?>">
    <label>Profile Picture: <input type="file" name="profile_pic" accept="image/*"></label>
    <button type="submit">Add Student</button>
</form>

<?php require 'footer.php'; ?>