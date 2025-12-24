/**  add additonal queries for any alter queries / inbulit insert queries  **/

/**  date:24/12/2025 **/

ALTER TABLE `job_posts` ADD `post_status` ENUM('draft','published') NOT NULL DEFAULT 'draft' AFTER `requirements`;