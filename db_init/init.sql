 CREATE DATABASE IF NOT EXISTS my_app_db;
USE my_app_db;

CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- You can add some initial data if you like
-- INSERT INTO notes (title, content) VALUES ('My First Note', 'This is a test note for the application.');
-- INSERT INTO notes (title, content) VALUES ('Docker Project', 'Remember to document everything!');

-- In a production environment, create a less privileged user.
-- For this assignment, the web app uses the root user for simplicity.
-- Example for creating a dedicated user:
-- CREATE USER 'app_user'@'%' IDENTIFIED BY 'app_password_secret';
-- GRANT ALL PRIVILEGES ON my_app_db.* TO 'app_user'@'%';
-- FLUSH PRIVILEGES;       