-- Script membuat database dan tabel
CREATE DATABASE IF NOT EXISTS vuln_notes;
USE vuln_notes;

-- Tabel Users (Catatan: Password diubah jadi VARCHAR(255) untuk menyimpan hash dari password_hash)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
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

-- Insert Catatan Dummy dicabut beserta pengguna statis karena alasan keamanan, dan juga sesuai request "jangan ada insert admin yang statis"
