CREATE TABLE helpdesk_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_number VARCHAR(255) NOT NULL UNIQUE,
    status VARCHAR(20) NOT NULL,
    priority VARCHAR(10) NOT NULL,
    concern_text VARCHAR(255) NOT NULL,
    transfer_reason VARCHAR(255)
);
