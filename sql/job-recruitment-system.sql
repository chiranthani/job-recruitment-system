-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 11:09 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job-recruitment-system`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_status` enum('Applied','In Review','Interview','Rejected','Offer Made','Offer Rejected','Offer Accepted','Hired') NOT NULL DEFAULT 'Applied',
  `candidate_name` varchar(255) NOT NULL,
  `contact_number` varchar(25) NOT NULL,
  `candidate_email` varchar(255) NOT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `current_role` varchar(255) DEFAULT NULL,
  `cv_url` varchar(255) NOT NULL,
  `notice_period` varchar(100) DEFAULT NULL,
  `applied_at` date NOT NULL,
  `interview_at` timestamp NULL DEFAULT NULL,
  `feedback` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `benefits`
--

CREATE TABLE `benefits` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `benefits`
--

INSERT INTO `benefits` (`id`, `name`, `status`, `createdAt`) VALUES
(1, 'Receive a competitive salary based on experience and performance.', 1, '2025-12-04 05:51:59'),
(2, 'Comprehensive health insurance coverage.', 1, '2025-12-04 05:51:59'),
(3, 'Company-provided meals or meal allowances for added convenience.', 1, '2025-12-04 05:51:59'),
(4, 'Eligibility for performance-based bonuses and incentives.', 1, '2025-12-04 05:51:59'),
(5, 'Travel and transport allowances.', 1, '2025-12-04 05:51:59'),
(6, 'Employee discounts on company products or partner services.', 1, '2025-12-04 05:51:59'),
(7, 'A positive and collaborative work culture that values teamwork and respect.', 1, '2025-12-04 05:51:59'),
(8, 'Professional development programs to help you grow your career.', 1, '2025-12-04 05:51:59'),
(9, 'Access to training workshops, certifications, and learning platforms.', 1, '2025-12-04 05:51:59'),
(10, 'Flexible working hours to support your personal and professional life.Professional development programs to help you grow your career.', 1, '2025-12-04 05:51:59'),
(11, 'Remote work opportunities for added convenience and flexibility.', 1, '2025-12-04 05:51:59'),
(12, 'Hybrid work options that allow you to split your time between home and office.', 1, '2025-12-04 05:51:59');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contact_no` varchar(15) DEFAULT NULL,
  `country` varchar(30) NOT NULL DEFAULT 'Sri Lanka',
  `location_id` int(11) DEFAULT NULL COMMENT 'city',
  `postal_code` int(11) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `cv_url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_jobs`
--

CREATE TABLE `candidate_jobs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `type` enum('Applied Job','Saved Job') NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `registration_no` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `website_link` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 - active | 0 - inactive',
  `admin_approval` enum('PENDING','REJECTED','APPROVED') NOT NULL DEFAULT 'PENDING',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_categories`
--

CREATE TABLE `job_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `icon_path` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_categories`
--

INSERT INTO `job_categories` (`id`, `name`, `icon_path`, `status`, `createdAt`) VALUES
(1, 'Information Technology & Software', 'assets/images/category_icons/it.png', 1, '2025-12-04 05:41:56'),
(2, 'Engineering', 'assets/images/category_icons/eng.png', 1, '2025-12-04 05:41:56'),
(3, 'Customer Service & Support', 'assets/images/category_icons/cs_sup.png', 1, '2025-12-04 05:41:56'),
(4, 'Sales & Marketing', 'assets/images/category_icons/marketing.png', 1, '2025-12-04 05:41:56'),
(5, 'Finance & Accounting', 'assets/images/category_icons/account_fin.png', 1, '2025-12-04 05:41:56'),
(6, 'Human Resources & Recruitment', 'assets/images/category_icons/hr.png', 1, '2025-12-04 05:41:56'),
(7, 'Administration & Office Support', 'assets/images/category_icons/admin.png', 1, '2025-12-04 05:41:56'),
(8, 'Healthcare & Medical', 'assets/images/category_icons/health.png', 1, '2025-12-04 05:41:56'),
(9, 'Manufacturing & Production', 'assets/images/category_icons/prod.png', 1, '2025-12-04 05:41:56'),
(10, 'Logistics, Supply Chain & Warehouse', 'assets/images/category_icons/logistic.png', 1, '2025-12-04 05:41:56'),
(11, 'Hospitality & Tourism', 'assets/images/category_icons/tourism.png', 1, '2025-12-04 05:41:56'),
(12, 'Creative, Media & Design', 'assets/images/category_icons/design.png', 1, '2025-12-04 05:41:56'),
(13, 'Government & Public Sector', 'assets/images/category_icons/gov.png', 1, '2025-12-04 05:41:56'),
(14, 'Telecommunications', 'assets/images/category_icons/tel.png', 1, '2025-12-04 05:41:56'),
(15, 'Science & Research', 'assets/images/category_icons/research.png', 1, '2025-12-04 05:41:56'),
(16, 'Other', 'assets/images/category_icons/other.png', 1, '2025-12-04 05:41:56');

-- --------------------------------------------------------

--
-- Table structure for table `job_posts`
--

CREATE TABLE `job_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `company_id` int(11) NOT NULL,
  `work_type` enum('On-site','Remote','Hybrid','') NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `job_type` enum('Full-Time','Part-Time','Contract','Internship','Freelance') NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `published_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `active_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 - active | 0 - inactive',
  `created_by` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 - yes  | 0 - no',
  `deletedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_post_benefits`
--

CREATE TABLE `job_post_benefits` (
  `id` int(11) NOT NULL,
  `job_post_id` int(11) NOT NULL,
  `benefit_id` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `status`, `createdAt`, `updatedAt`) VALUES
(1, 'Colombo', 1, '2025-12-06 13:29:08', NULL),
(2, 'Colombo 01 - Fort', 1, '2025-12-06 13:29:08', NULL),
(3, 'Colombo 02 - Slave Island', 1, '2025-12-06 13:29:08', NULL),
(4, 'Colombo 03 - Kollupitiya', 1, '2025-12-06 13:29:08', NULL),
(5, 'Colombo 04 - Bambalapitiya', 1, '2025-12-06 13:29:08', NULL),
(6, 'Colombo 05 - Havelock Town/Narahenpita', 1, '2025-12-06 13:29:08', NULL),
(7, 'Colombo 06 - Wellawatte', 1, '2025-12-06 13:29:08', NULL),
(8, 'Colombo 07 - Cinnamon Gardens', 1, '2025-12-06 13:29:08', NULL),
(9, 'Colombo 08 - Borella', 1, '2025-12-06 13:29:08', NULL),
(10, 'Colombo 10 - Maradana', 1, '2025-12-06 13:29:08', NULL),
(11, 'Colombo 11 - Pettah', 1, '2025-12-06 13:29:08', NULL),
(12, 'Colombo 12 - Aluthkade', 1, '2025-12-06 13:29:08', NULL),
(13, 'Colombo 13 - Kotahena', 1, '2025-12-06 13:29:08', NULL),
(14, 'Colombo 14 - Grandpass', 1, '2025-12-06 13:29:08', NULL),
(15, 'Colombo 15 - Modara', 1, '2025-12-06 13:29:08', NULL),
(16, 'Dehiwala', 1, '2025-12-06 13:29:08', NULL),
(17, 'Mount Lavinia', 1, '2025-12-06 13:29:08', NULL),
(18, 'Maharagama', 1, '2025-12-06 13:29:08', NULL),
(19, 'Nugegoda', 1, '2025-12-06 13:29:08', NULL),
(20, 'Malabe', 1, '2025-12-06 13:29:08', NULL),
(21, 'Battaramulla', 1, '2025-12-06 13:29:08', NULL),
(22, 'Kottawa', 1, '2025-12-06 13:29:08', NULL),
(23, 'Moratuwa', 1, '2025-12-06 13:29:08', NULL),
(24, 'Panadura', 1, '2025-12-06 13:29:08', NULL),
(25, 'Kalutara', 1, '2025-12-06 13:29:08', NULL),
(26, 'Negombo', 1, '2025-12-06 13:29:08', NULL),
(27, 'Gampaha', 1, '2025-12-06 13:29:08', NULL),
(28, 'Wattala', 1, '2025-12-06 13:29:08', NULL),
(29, 'Ja-Ela', 1, '2025-12-06 13:29:08', NULL),
(30, 'Kandy', 1, '2025-12-06 13:29:08', NULL),
(31, 'Matale', 1, '2025-12-06 13:29:08', NULL),
(32, 'Nuwara Eliya', 1, '2025-12-06 13:29:08', NULL),
(33, 'Galle', 1, '2025-12-06 13:29:08', NULL),
(34, 'Matara', 1, '2025-12-06 13:29:08', NULL),
(35, 'Hambantota', 1, '2025-12-06 13:29:08', NULL),
(36, 'Jaffna', 1, '2025-12-06 13:29:08', NULL),
(37, 'Kilinochchi', 1, '2025-12-06 13:29:08', NULL),
(38, 'Mullaitivu', 1, '2025-12-06 13:29:08', NULL),
(39, 'Vavuniya', 1, '2025-12-06 13:29:08', NULL),
(40, 'Batticaloa', 1, '2025-12-06 13:29:08', NULL),
(41, 'Trincomalee', 1, '2025-12-06 13:29:08', NULL),
(42, 'Ampara', 1, '2025-12-06 13:29:08', NULL),
(43, 'Kurunegala', 1, '2025-12-06 13:29:08', NULL),
(44, 'Puttalam', 1, '2025-12-06 13:29:08', NULL),
(45, 'Anuradhapura', 1, '2025-12-06 13:29:08', NULL),
(46, 'Polonnaruwa', 1, '2025-12-06 13:29:08', NULL),
(47, 'Badulla', 1, '2025-12-06 13:29:08', NULL),
(48, 'Monaragala', 1, '2025-12-06 13:29:08', NULL),
(49, 'Ratnapura', 1, '2025-12-06 13:29:08', NULL),
(50, 'Kegalle', 1, '2025-12-06 13:29:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `createdAt` datetime DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `status`, `createdAt`, `updatedAt`) VALUES
(1, 'Candidate', 1, '2025-12-04 10:41:28', NULL),
(2, 'Recruiter', 1, '2025-12-04 10:41:28', NULL),
(3, 'Admin', 1, '2025-12-04 10:41:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('Technical','Common') DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `type`, `status`, `createdAt`, `updatedAt`) VALUES
(1, 'Java', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(2, 'Python', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(3, 'JavaScript', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(4, 'TypeScript', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(5, 'PHP', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(6, 'C', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(7, 'C++', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(8, 'Go', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(9, 'Ruby', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(10, 'Kotlin', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(11, 'HTML', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(12, 'CSS', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(13, 'Bootstrap', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(14, 'JS Frameworks', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(15, 'REST API', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(16, 'Android Development', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(17, 'iOS Development', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(18, 'Microsoft Azure', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(19, 'AWS', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(20, 'CI/CD', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(21, 'Git', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(22, 'Agile Methodology', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(23, 'UI Design', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(24, 'UX Research', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(25, 'Prototyping', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(26, 'Network Security', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(27, 'Data Visualization', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(28, 'Data Analysis', 'Technical', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(29, 'Communication Skills', 'Common', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(30, 'Teamwork', 'Common', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(31, 'Problem Solving', 'Common', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(32, 'Time Management', 'Common', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(33, 'Leadership', 'Common', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(34, 'Analytical Thinking', 'Common', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(35, 'Critical Thinking', 'Common', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(36, 'Creativity', 'Common', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(37, 'Prioritization', 'Common', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(38, 'Collaboration', 'Common', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(39, 'Negotiation', 'Common', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48'),
(40, 'Flexibility', 'Common', 1, '2025-12-06 08:03:42', '2025-12-06 12:33:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL COMMENT '1-Candidate, 2-Recruiter, 3-Admin',
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `profile_image` varchar(255) NOT NULL DEFAULT 'assets/images/default_profile_image.png',
  `gender` enum('Male','Female','Other') DEFAULT 'Other',
  `company_id` int(11) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `login_count` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 - active | 0 - inactive',
  `is_deleted` tinyint(1) DEFAULT 0 COMMENT '1 - yes | 0 - no',
  `deletedAt` datetime DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_skills`
--

CREATE TABLE `user_skills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applications_user_fk` (`user_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `benefits`
--
ALTER TABLE `benefits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `candidate_jobs`
--
ALTER TABLE `candidate_jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_no` (`registration_no`);

--
-- Indexes for table `job_categories`
--
ALTER TABLE `job_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_posts`
--
ALTER TABLE `job_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_posts__category_fk` (`category_id`),
  ADD KEY `job_posts_company_fk` (`company_id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `job_post_benefits`
--
ALTER TABLE `job_post_benefits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_post_benefits__benefit_fk` (`benefit_id`),
  ADD KEY `job_post_id` (`job_post_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`) USING BTREE,
  ADD KEY `users_role_fk` (`role_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `benefits`
--
ALTER TABLE `benefits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_jobs`
--
ALTER TABLE `candidate_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_categories`
--
ALTER TABLE `job_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `job_posts`
--
ALTER TABLE `job_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_post_benefits`
--
ALTER TABLE `job_post_benefits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_skills`
--
ALTER TABLE `user_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidates_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `candidate_jobs`
--
ALTER TABLE `candidate_jobs`
  ADD CONSTRAINT `candidate_jobs_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidate_jobs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_posts`
--
ALTER TABLE `job_posts`
  ADD CONSTRAINT `job_posts__category_fk` FOREIGN KEY (`category_id`) REFERENCES `job_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_posts_company_fk` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_posts_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_posts_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_post_benefits`
--
ALTER TABLE `job_post_benefits`
  ADD CONSTRAINT `job_post_benefits__benefit_fk` FOREIGN KEY (`benefit_id`) REFERENCES `benefits` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_post_benefits_ibfk_1` FOREIGN KEY (`job_post_id`) REFERENCES `job_posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_role_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD CONSTRAINT `user_skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
