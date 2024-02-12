-- Create the table for managing people
CREATE TABLE IF NOT EXISTS person (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    saIdNumber VARCHAR(13) NOT NULL,
    mobile_number VARCHAR(15) NOT NULL,
    email_address VARCHAR(255) NOT NULL,
    birth_date DATE NOT NULL,
    language VARCHAR(50) NOT NULL,
    interests TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create a trigger to send an email when a person is captured
DELIMITER //
CREATE TRIGGER person_after_insert
AFTER INSERT ON person
FOR EACH ROW
BEGIN
    -- You may need to adjust the email sending logic based on your setup
    -- This is just a placeholder for demonstration purposes
    INSERT INTO email_queue (to_email, subject, body)
    VALUES (NEW.email_address, 'Welcome to Our System', 'Dear ' || NEW.name || ', you have been captured in our system.');
END;
//
DELIMITER ;
