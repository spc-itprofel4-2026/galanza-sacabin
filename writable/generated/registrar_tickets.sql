CREATE TABLE registrar_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_number VARCHAR(255) NOT NULL,
    status VARCHAR(12) NOT NULL,
    priority VARCHAR(6) NOT NULL,
    concern_text VARCHAR(120) NOT NULL UNIQUE,
    transfer_reason VARCHAR(255)
);
