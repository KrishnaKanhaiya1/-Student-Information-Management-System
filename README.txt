Student Information Management System

Project Overview
A web-based PHP application to manage student information, including adding, viewing, searching, updating records, file operations, session/cookie management, and MySQL database connectivity.

Features
- Add, view, search, update, and delete student records
- File upload for student profile pictures
- Session and cookie management
- Error and exception handling
- Logging of student add/delete actions

Setup Instructions
1. Requirements
   - PHP 7.x or higher
   - MySQL
   - Web server (e.g., XAMPP, WAMP, LAMP)

2. Database Setup
   - Import the `students.sql` file into your MySQL server to create the required database and table.

3. Configuration
   - Update database credentials in `db.php` if needed.

4. Running the Project
   - Place all project files in your web server's root directory (e.g., `htdocs` for XAMPP).
   - Access the application via `http://localhost/student_management/index.php` in your browser.

Pages
- `index.php` – Login and home page
- `add_student.php` – Add student form
- `view_students.php` – View all students
- `search_student.php` – Search student records
- `logs.php` – View log file

Notes
- Profile pictures are stored in the `uploads/` directory.
- Log file is `student_log.txt`.
- User preferences (theme) are stored in cookies.
