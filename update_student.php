<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
require 'db.php';
if (!isset($_GET['id'])) {
    header('Location: view_students.php');
    exit();
}
$id = intval($_GET['id']);
$res = $conn->query("SELECT * FROM students WHERE id=$id");
$stu = $res->fetch_assoc();
if (!$stu) {
    header('Location: view_students.php');
    exit();
}
$error = $success = '';
$name = $stu['name'];
$email = $stu['email'];
$dob = $stu['dob'];
$course = $stu['course'];
$grade = $stu['grade'];
$profile_pic = $stu['profile_pic'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $dob = $_POST['dob'];
    $course = trim($_POST['course']);
    $grade = trim($_POST['grade']);
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
                if ($profile_pic && file_exists($profile_pic)) unlink($profile_pic);
                $profile_pic = 'uploads/' . uniqid('stu_', true) . '.' . $ext;
                move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_pic);
            } else {
                $error = 'Invalid file type.';
            }
        }
        if (!$error) {
            $stmt = $conn->prepare("UPDATE students SET name=?, email=?, dob=?, course=?, grade=?, profile_pic=? WHERE id=?");
            $stmt->bind_param('ssssssi', $name, $email, $dob, $course, $grade, $profile_pic, $id);
            try {
                $stmt->execute();
                $success = 'Student updated successfully!';
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
    <title>Update Student</title>
    <style>body{font-family:Arial,sans-serif;}form{max-width:400px;margin:40px auto;padding:24px;border-radius:8px;background:#f9f9f9;box-shadow:0 2px 8px #0002;}input,select{width:100%;padding:8px;margin:8px 0;}img{max-width:80px;max-height:80px;display:block;margin-bottom:8px;}</style>
</head>
<body>
<form method="post" enctype="multipart/form-data">
    <h2>Update Student</h2>
    <?php if ($error): ?><div style="color:red;"> <?= $error ?> </div><?php endif; ?>
    <?php if ($success): ?><div style="color:green;"> <?= $success ?> </div><?php endif; ?>
    <input type="text" name="name" placeholder="Name" value="<?= htmlspecialchars($name) ?>" required>
    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required>
    <input type="date" name="dob" value="<?= htmlspecialchars($dob) ?>" required>
    <input type="text" name="course" placeholder="Course" value="<?= htmlspecialchars($course) ?>" required>
    <input type="text" name="grade" placeholder="Grade (optional)" value="<?= htmlspecialchars($grade) ?>">
    <?php if ($profile_pic): ?><img src="<?= $profile_pic ?>"><?php endif; ?>
    <label>Change Profile Picture: <input type="file" name="profile_pic" accept="image/*"></label>
    <button type="submit">Update Student</button>
    <div style="margin-top:12px;"><a href="view_students.php">Back to Students</a></div>
</form>
</body>
</html> 