<?php
require 'functions.php';
require 'header.php';

$log_file = 'student_log.txt';
$log_content = file_exists($log_file) ? file_get_contents($log_file) : 'No logs found.';
?>

<h2 style="text-align:center;">Student Add/Delete Logs</h2>
<pre style="background:#f0f0f0; padding:15px; border-radius:5px;"><?= htmlspecialchars($log_content) ?></pre>

<?php require 'footer.php'; ?>