-- Database: habit_tracker

CREATE DATABASE IF NOT EXISTS habit_tracker;
USE habit_tracker;

-- Table: users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: habits
CREATE TABLE habits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    habit_name VARCHAR(100) NOT NULL,
    created_date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table: habit_completions
CREATE TABLE habit_completions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    habit_id INT NOT NULL,
    completion_date DATE NOT NULL,
    FOREIGN KEY (habit_id) REFERENCES habits(id) ON DELETE CASCADE,
    UNIQUE KEY unique_completion (habit_id, completion_date)
);

-- Sample Data (Optional)
INSERT INTO users (username, email, password) VALUES 
('demo', 'demo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO habits (user_id, habit_name, created_date) VALUES 
(1, 'Morning Exercise', CURDATE()),
(1, 'Read 30 Minutes', CURDATE()),
(1, 'Drink 8 Glasses Water', CURDATE());