-- Student Management System Database

-- Create database
CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

-- Users table (for authentication)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('student', 'principal', 'stream_head') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Student profiles
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    address TEXT NOT NULL,
    contact_number VARCHAR(15) NOT NULL,
    parent_name VARCHAR(100) NOT NULL,
    parent_contact VARCHAR(15) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- O/L Results
CREATE TABLE IF NOT EXISTS ol_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject VARCHAR(50) NOT NULL,
    grade ENUM('A', 'B', 'C', 'S', 'W', 'F') NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- A/L Streams
CREATE TABLE IF NOT EXISTS streams (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    head_user_id INT,
    FOREIGN KEY (head_user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- A/L Subjects
CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    stream_id INT NOT NULL,
    FOREIGN KEY (stream_id) REFERENCES streams(id) ON DELETE CASCADE
);

-- Applications
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    stream_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_by INT,
    reviewed_at TIMESTAMP NULL,
    comments TEXT,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (stream_id) REFERENCES streams(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Application Subjects (many-to-many relationship)
CREATE TABLE IF NOT EXISTS application_subjects (
    application_id INT NOT NULL,
    subject_id INT NOT NULL,
    PRIMARY KEY (application_id, subject_id),
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

-- Insert default streams
INSERT INTO streams (name, description) VALUES 
('Bio Science', 'Biology, Chemistry, Physics and related subjects'),
('Mathematics', 'Combined Mathematics, Physics, Chemistry and related subjects'),
('Commerce', 'Business Studies, Economics, Accounting and related subjects'),
('Arts', 'Languages, History, Geography and related subjects'),
('Technology', 'Engineering Technology, Science for Technology and related subjects');

-- Insert default subjects for each stream
-- Bio Science
INSERT INTO subjects (name, stream_id) VALUES 
('Biology', 1),
('Chemistry', 1),
('Physics', 1);

-- Mathematics
INSERT INTO subjects (name, stream_id) VALUES 
('Combined Mathematics', 2),
('Physics', 2),
('Chemistry', 2);

-- Commerce
INSERT INTO subjects (name, stream_id) VALUES 
('Business Studies', 3),
('Economics', 3),
('Accounting', 3);

-- Arts
INSERT INTO subjects (name, stream_id) VALUES 
('History', 4),
('Geography', 4),
('Political Science', 4),
('Languages', 4);

-- Technology
INSERT INTO subjects (name, stream_id) VALUES 
('Engineering Technology', 5),
('Science for Technology', 5),
('Information Communication Technology', 5);

-- Insert default admin account (Principal)
-- Default password is 'admin123' (hashed)
INSERT INTO users (username, password, email, role) VALUES 
('principal', '$2y$10$8tGIx5g5s5q5X5X5X5X5X.5X5X5X5X5X5X5X5X5X5X5X5X5X5X5X5', 'principal@school.edu', 'principal');

-- Insert default stream heads
INSERT INTO users (username, password, email, role) VALUES 
('biohead', '$2y$10$8tGIx5g5s5q5X5X5X5X5X.5X5X5X5X5X5X5X5X5X5X5X5X5X5X5X5', 'biohead@school.edu', 'stream_head'),
('mathhead', '$2y$10$8tGIx5g5s5q5X5X5X5X5X.5X5X5X5X5X5X5X5X5X5X5X5X5X5X5X5', 'mathhead@school.edu', 'stream_head'),
('comhead', '$2y$10$8tGIx5g5s5q5X5X5X5X5X.5X5X5X5X5X5X5X5X5X5X5X5X5X5X5X5', 'comhead@school.edu', 'stream_head'),
('artshead', '$2y$10$8tGIx5g5s5q5X5X5X5X5X.5X5X5X5X5X5X5X5X5X5X5X5X5X5X5X5', 'artshead@school.edu', 'stream_head'),
('techhead', '$2y$10$8tGIx5g5s5q5X5X5X5X5X.5X5X5X5X5X5X5X5X5X5X5X5X5X5X5X5', 'techhead@school.edu', 'stream_head');

-- Update stream heads
UPDATE streams SET head_user_id = (SELECT id FROM users WHERE username = 'biohead') WHERE id = 1;
UPDATE streams SET head_user_id = (SELECT id FROM users WHERE username = 'mathhead') WHERE id = 2;
UPDATE streams SET head_user_id = (SELECT id FROM users WHERE username = 'comhead') WHERE id = 3;
UPDATE streams SET head_user_id = (SELECT id FROM users WHERE username = 'artshead') WHERE id = 4;
UPDATE streams SET head_user_id = (SELECT id FROM users WHERE username = 'techhead') WHERE id = 5;

