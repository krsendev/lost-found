CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100),
    password VARCHAR(255),
    otp VARCHAR(6),
    otp_expired DATETIME,
    otp_attempt INT DEFAULT 0
);