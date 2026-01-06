/**  add additonal queries for any alter queries / inbulit insert queries  **/

/**  date:24/12/2025 **/

ALTER TABLE `job_posts` ADD `post_status` ENUM('draft','published') NOT NULL DEFAULT 'draft' AFTER `requirements`;

/**  date: 6/1/2025 **/
ALTER TABLE `candidates` CHANGE `createdAt` `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `candidates` CHANGE `updatedAt` `updatedAt` DATETIME NULL DEFAULT NULL;
ALTER TABLE `user_skills` CHANGE `createdAt` `createdAt` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `user_skills` CHANGE `updatedAt` `updatedAt` DATETIME NULL DEFAULT NULL;
ALTER TABLE `user_skills` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '1';
