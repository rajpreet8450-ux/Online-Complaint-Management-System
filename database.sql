CREATE DATABASE IF NOT EXISTS complaint_management
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE complaint_management;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('student','admin') NOT NULL DEFAULT 'student',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS complaints (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(150) NOT NULL,
  category VARCHAR(60) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('Submitted','Under Review','In Progress','Resolved') NOT NULL DEFAULT 'Submitted',
  admin_remarks TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_complaints_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS sessions (
  id VARCHAR(128) NOT NULL PRIMARY KEY,
  payload MEDIUMBLOB NOT NULL,
  last_activity INT UNSIGNED NOT NULL,
  INDEX idx_sessions_last_activity (last_activity)
);
