-- SimpleFinance Manager - Database Schema
-- 1) Create a database in phpMyAdmin first (example: simplefinance)
-- 2) Select that database
-- 3) Run this file

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(120) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  type ENUM('income','expense') NOT NULL,
  category VARCHAR(60) NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  description VARCHAR(255) NULL,
  date DATE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_transactions_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,
  INDEX idx_transactions_user_date (user_id, date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
