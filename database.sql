CREATE DATABASE IF NOT EXISTS hostel_management;
USE hostel_management;


CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL 
);


CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    phone_number VARCHAR(20),
    fathers_name VARCHAR(100),
    mothers_name VARCHAR(100),
    guardian_phone VARCHAR(20),
    mission VARCHAR(255),
    address TEXT,
    blood_group VARCHAR(10),
    education_level VARCHAR(100),
    college_name VARCHAR(150),
    join_date DATE
);


CREATE TABLE IF NOT EXISTS fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100), 
    month VARCHAR(20),
    year INT,
    amount DECIMAL(10, 2),
    status ENUM('Paid', 'Unpaid') DEFAULT 'Unpaid',
    paid_date DATE
);
