<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
require 'db.php';
$error = $success = '';
$name = $email = $dob = $course = $grade = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $dob = $_POST['dob'];
    $course = trim($_POST['course']);
    $grade = trim($_POST['grade']);
    $profile_pic = '';
    // Validate
    if (!$name || !$email || !$dob || !$course) {
        $error = 'Please fill all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        // Handle file upload
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg','jpeg','png','gif'];
            if (in_array(strtolower($ext), $allowed)) {
                if (!is_dir('uploads')) mkdir('uploads');
                $profile_pic = 'uploads/' . uniqid('stu_', true) . '.' . $ext;
                move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_pic);
            } else {
                $error = 'Invalid file type.';
            }
        }
        if (!$error) {
            $stmt = $conn->prepare("INSERT INTO students (name, email, dob, course, grade, profile_pic) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssss', $name, $email, $dob, $course, $grade, $profile_pic);
            try {
                $stmt->execute();
                // Log action
                file_put_contents('student_log.txt', date('Y-m-d H:i:s') . " - Added: $name ($email)\n", FILE_APPEND);
                $success = 'Student added successfully!';
                $name = $email = $dob = $course = $grade = '';
            } catch (Exception $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <style>body{font-family:Arial,sans-serif;}form{max-width:400px;margin:40px auto;padding:24px;border-radius:8px;background:#f9f9f9;box-shadow:0 2px 8px #0002;}input,select{width:100%;padding:8px;margin:8px 0;}</style>
</head>
<body>
<form method="post" enctype="multipart/form-data">
    <h2>Add Student</h2>
    <?php if ($error): ?><div style="color:red;"> <?= $error ?> </div><?php endif; ?>
    <?php if ($success): ?><div style="color:green;"> <?= $success ?> </div><?php endif; ?>
    <input type="text" name="name" placeholder="Name" value="<?= htmlspecialchars($name) ?>" required>
    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
    <input type="date" name="dob" value="<?= htmlspecialchars($dob) ?>" required>
    <input type="text" name="course" placeholder="Course" value="<?= htmlspecialchars($course) ?>" required>
    <input type="text" name="grade" placeholder="Grade (optional)" value="<?= htmlspecialchars($grade) ?>">
    <label>Profile Picture: <input type="file" name="profile_pic" accept="image/*"></label>
    <button type="submit">Add Student</button>
    <div style="margin-top:12px;"><a href="index.php">Back to Home</a></div>
</form>
</body>
</html> 