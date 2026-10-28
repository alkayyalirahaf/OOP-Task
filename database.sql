
CREATE DATABASE IF NOT EXISTS mydb;
use mydb;

CREATE TABLE IF NOT EXISTS users(
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(50) NOT NULL,
email VARCHAR(100) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



INSERT INTO users (name, email, password, role) 
VALUES ('Admin', 'admin@test.com', '$2y$10$eImiTXuWVxfM37uY4JANjQ==...', 'admin');


UPDATE users 
SET password ='$2y$10$KFpbafOap4oU72At99CXI.0cW8.6cXElKKN.SdiC/g00T7TZjbt2W'
WHERE email = 'admin@test.com';