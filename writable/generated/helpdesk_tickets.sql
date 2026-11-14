CREATE TABLE helpdesk_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_name VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    priority INT NOT NULL,
    is_resolved TINYINT(1),
    submitted_at DATETIME NOT NULL
);
