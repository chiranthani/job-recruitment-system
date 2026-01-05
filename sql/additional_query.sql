/**  add additonal queries for any alter queries / inbulit insert queries  **/

/**  date:24/12/2025 **/

ALTER TABLE `job_posts` ADD `post_status` ENUM('draft','published') NOT NULL DEFAULT 'draft' AFTER `requirements`;

/** date: 4/1/2024 **/
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'receiver',        
    sender_id INT NOT NULL COMMENT "who triggered",
    application_id INT NULL,
    type VARCHAR(50) NOT NULL,
    message VARCHAR(255) NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);