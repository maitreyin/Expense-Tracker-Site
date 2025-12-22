CREATE DATABASE trackexpa;
USE trackexpa;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    -- Password needs to be VARCHAR(255) to store hashed passwords
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Link to the users table
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    -- Amount will be stored with two decimal places
    amount DECIMAL(10,2) NOT NULL,
    -- Type will be 'income' or 'expense'
    type VARCHAR(20) NOT NULL,
    -- Column for the date the user input for the entry
    entry_date DATE NOT NULL,
    
    -- Set the Foreign Key constraint for data integrity
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);