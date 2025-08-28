-- Database setup for CRUD operations
-- Create database
CREATE DATABASE IF NOT EXISTS adsindia_db;
USE adsindia_db;

-- Create users table for demonstration
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO users (name, email, phone, address) VALUES
('John Doe', 'john@example.com', '1234567890', '123 Main St, City'),
('Jane Smith', 'jane@example.com', '0987654321', '456 Oak Ave, Town'),
('Bob Johnson', 'bob@example.com', '5555555555', '789 Pine Rd, Village');