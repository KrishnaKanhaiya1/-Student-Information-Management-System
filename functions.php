<?php
// functions.php

function connect_db() {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'student_management';

    try {
        $conn = new mysqli($host, $user, $pass, $db);
        if ($conn->connect_error) {
            throw new Exception('Database connection failed: ' . $conn->connect_error);
        }
        return $conn;
    } catch (Exception $e) {
        die('Error: ' . $e->getMessage());
    }
}

function log_action($message) {
    $log_file = 'student_log.txt';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "$timestamp - $message\n", FILE_APPEND);
}

function handle_profile_pic_upload($file_input, $current_pic = '') {
    if (isset($_FILES[$file_input]) && $_FILES[$file_input]['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $_FILES[$file_input]['tmp_name']);
        finfo_close($file_info);

        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($mime_type, $allowed_mime_types)) {
            return ['error' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.'];
        }

        if ($current_pic && file_exists($current_pic)) {
            unlink($current_pic);
        }

        $ext = pathinfo($_FILES[$file_input]['name'], PATHINFO_EXTENSION);
        $new_filename = $upload_dir . uniqid('stu_', true) . '.' . $ext;

        if (move_uploaded_file($_FILES[$file_input]['tmp_name'], $new_filename)) {
            return ['filepath' => $new_filename];
        } else {
            return ['error' => 'Failed to move uploaded file.'];
        }
    }
    return ['filepath' => $current_pic];
}
?>