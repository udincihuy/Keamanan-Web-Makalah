-- Script membuat database dan tabel
CREATE DATABASE IF NOT EXISTS vuln_notes;
USE vuln_notes;

-- Tabel Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE
);

-- Tabel Notes
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(100),
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert Data Dummy
INSERT INTO users (username, password, is_admin) VALUES 
('admin', 'admin123', 1),
('rahasia_admin', 'supersemar99', 1),
('johndoe', 'password123', 0),
('janedoe', 'rahasia123', 0);

-- Insert Catatan Dummy
INSERT INTO notes (user_id, title, content) VALUES
(1, 'Password Server', 'Server IP: 192.168.1.100, Root Pass: roottoor123!'),
(3, 'Tugas Kuliah', 'Jangan lupa submit makalah keamanan web sebelum jumat.'),
(3, 'Resep Rahasia', 'Bahan rahasia krabby patty adalah... cincau.'),
(4, 'Catatan Harian', 'Hari ini belajar tentang SQL injection, seru banget!');