-- SQL to create the students database and students table
CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    dob DATE NOT NULL,
    course VARCHAR(100) NOT NULL,
    grade VARCHAR(10),
    profile_pic VARCHAR(255)
); 