CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Default user: admin / admin123
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$DCgGZpAMc3o2n3j9R0tL0OQzH.2rL9.iE9.Y8.j7a4I5jX3Yg8C3G');