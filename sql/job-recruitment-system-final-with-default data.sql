-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 18, 2026 at 05:19 PM
-- Server version: 5.7.31
-- PHP Version: 8.0.23

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

DROP TABLE IF EXISTS `applications`;
CREATE TABLE IF NOT EXISTS `applications` (
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
  `applied_at` date DEFAULT NULL,
  `interview_at` timestamp NULL DEFAULT NULL,
  `feedback` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `applications_user_fk` (`user_id`),
  KEY `job_id` (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `job_id`, `user_id`, `application_status`, `candidate_name`, `contact_number`, `candidate_email`, `experience`, `current_role`, `cv_url`, `notice_period`, `applied_at`, `interview_at`, `feedback`, `status`, `createdAt`, `updatedAt`) VALUES
(1, 6, 3, 'Applied', 'uoc user', '710000000', 'jobseeker@example.com', '1-2 years', 'Project Coordinator', 'assets/uploads/resumes/1768148804_cv-dummy-intern-developer.pdf', '1 Month', '2026-01-16', NULL, NULL, 1, '2026-01-16 19:42:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `benefits`
--

DROP TABLE IF EXISTS `benefits`;
CREATE TABLE IF NOT EXISTS `benefits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

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

DROP TABLE IF EXISTS `candidates`;
CREATE TABLE IF NOT EXISTS `candidates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `contact_no` varchar(15) DEFAULT NULL,
  `country` varchar(30) NOT NULL DEFAULT 'Sri Lanka',
  `location_id` int(11) DEFAULT NULL COMMENT 'city',
  `postal_code` int(11) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `bio` text,
  `cv_url` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `location_id` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `user_id`, `contact_no`, `country`, `location_id`, `postal_code`, `job_title`, `bio`, `cv_url`, `status`, `createdAt`, `updatedAt`) VALUES
(1, 3, '710000000', 'Sri Lanka', 1, 0, 'Intern Developer', 'A motivated Intern Developer with a strong foundation in programming and web application development. Passionate about learning new technologies and building practical solutions through hands-on experience. Familiar with modern development tools, eager to contribute to team projects, and continuously improve problem-solving and coding skills in a professional environment.', 'assets/uploads/resumes/1768148804_cv-dummy-intern-developer.pdf', 1, '2026-01-11 21:56:40', '2026-01-11 21:56:44'),
(2, 10, '', 'Sri Lanka', 2, 0, 'None', 'testing', 'assets/uploads/resumes/1768708673_AsithWickramage.pdf', 1, '2026-01-18 09:27:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `candidate_jobs`
--

DROP TABLE IF EXISTS `candidate_jobs`;
CREATE TABLE IF NOT EXISTS `candidate_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `type` enum('Applied Job','Saved Job') NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_id` (`job_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `candidate_jobs`
--

INSERT INTO `candidate_jobs` (`id`, `user_id`, `job_id`, `type`, `createdAt`, `updatedAt`) VALUES
(1, 3, 6, 'Applied Job', '2026-01-16 19:42:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `registration_no` varchar(200) DEFAULT NULL,
  `description` text,
  `address` varchar(255) DEFAULT NULL,
  `website_link` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - active | 0 - inactive',
  `admin_approval` enum('PENDING','REJECTED','APPROVED') NOT NULL DEFAULT 'PENDING',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `registration_no` (`registration_no`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `registration_no`, `description`, `address`, `website_link`, `status`, `admin_approval`, `createdAt`, `updatedAt`) VALUES
(1, 'ABC Solutions (PVT) Ltd', 'PV000000', NULL, NULL, NULL, 1, 'APPROVED', '2026-01-11 21:15:58', NULL),
(2, 'abc corp', '1234', NULL, NULL, NULL, 1, 'APPROVED', '2026-01-15 19:50:20', '2026-01-16 08:05:31'),
(3, 'xyz corp', '1122', NULL, NULL, NULL, 1, 'REJECTED', '2026-01-16 07:51:21', '2026-01-16 08:04:06'),
(4, 'Virtusa pvt', 'R0114', '', '', '', 0, 'APPROVED', '2026-01-16 07:54:42', '2026-01-16 08:21:51'),
(5, 'Miracle Cafe', '2345', NULL, NULL, NULL, 1, 'APPROVED', '2026-01-16 19:17:41', '2026-01-16 19:20:14'),
(6, 'UK Express', 'R0113', '', 'England', '', 1, 'APPROVED', '2026-01-17 05:38:56', '2026-01-17 06:29:35'),
(7, 'Test', '1000001', 'testing', 'test', '', 1, 'APPROVED', '2026-01-18 09:17:42', '2026-01-18 09:19:06'),
(8, 'Epic', 'R123', NULL, NULL, NULL, 1, 'PENDING', '2026-01-18 09:40:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_categories`
--

DROP TABLE IF EXISTS `job_categories`;
CREATE TABLE IF NOT EXISTS `job_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `icon_path` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

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
(14, 'Telecommunications', 'assets/images/category_icons/tel.png', 1, '2025-12-04 05:41:56'),
(15, 'Science & Research', 'assets/images/category_icons/research.png', 1, '2025-12-04 05:41:56'),
(16, 'Other', 'assets/images/category_icons/other.png', 1, '2025-12-04 05:41:56');

-- --------------------------------------------------------

--
-- Table structure for table `job_posts`
--

DROP TABLE IF EXISTS `job_posts`;
CREATE TABLE IF NOT EXISTS `job_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `company_id` int(11) NOT NULL,
  `work_type` enum('On-site','Remote','Hybrid','') NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `job_type` enum('Full-Time','Part-Time','Contract','Internship','Freelance') NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `requirements` text NOT NULL,
  `post_status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `published_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `active_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - active | 0 - inactive',
  `created_by` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 - yes  | 0 - no',
  `deletedAt` timestamp NULL DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_posts__category_fk` (`category_id`),
  KEY `job_posts_company_fk` (`company_id`),
  KEY `location_id` (`location_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `job_posts`
--

INSERT INTO `job_posts` (`id`, `title`, `company_id`, `work_type`, `location_id`, `job_type`, `category_id`, `description`, `requirements`, `post_status`, `published_date`, `expiry_date`, `active_status`, `created_by`, `is_deleted`, `deletedAt`, `createdAt`, `updatedAt`) VALUES
(4, 'Software Engineer', 1, 'Hybrid', 1, 'Full-Time', 1, 'As a Software Engineer, you will work within an agile environment to build scalable and efficient applications. You will be involved in the entire software development life cycle, from initial concept and architecture to coding, testing, and deployment.\r\nYou\'ll collaborate closely with cross-functional teams including product managers and designers to ensure our software meets technical requirements and delivers an exceptional user experience.', 'Technical Skills:\r\n\r\nProven experience as a Software Engineer or in a similar software development role.\r\n\r\nProficiency in modern programming languages (such as Java, Python, C#, or JavaScript/TypeScript).\r\n\r\nSolid understanding of software design patterns and system architecture.\r\n\r\nExperience with relational or non-relational databases (e.g., PostgreSQL, MongoDB, or MySQL).\r\n\r\nFamiliarity with version control systems, specifically Git.\r\n\r\nCore Competencies:\r\n\r\nProblem-Solving: Ability to troubleshoot complex issues and optimize code for performance and scalability.\r\n\r\nCollaboration: Strong communication skills and a desire to work effectively in a team-oriented, hybrid environment.\r\n\r\nAdaptability: A continuous learner who stays up-to-date with emerging technologies and industry trends.\r\n\r\nEducation: Bachelor\'s degree in Computer Science, Information Technology, or a related field (or equivalent practical experience).', 'published', '2026-01-15', '2026-01-31', 1, 2, 0, NULL, '2026-01-15 07:54:48', NULL),
(5, 'Call Center Agent', 1, 'Remote', 27, 'Part-Time', 14, 'As a Call Center Agent, you will be the primary point of contact for our customers. Your main goal is to provide high-quality support by handling inbound calls, resolving service inquiries, and ensuring a positive customer experience. You will navigate our telecommunications database to provide accurate information regarding plans, billing, and technical troubleshooting, all while maintaining a professional and friendly demeanor.', 'Technical & Workspace Requirements:\r\n\r\nRemote Setup: Access to a quiet, distraction-free home office space.\r\n\r\nEquipment: A reliable high-speed internet connection and a functional computer/laptop.\r\n\r\nTech Savvy: Ability to quickly learn and navigate new software and multi-line phone systems.\r\n\r\nSkills & Qualifications:\r\n\r\nCommunication: Exceptional verbal and written communication skills with a clear speaking voice.\r\n\r\nCustomer Focus: A patient, empathetic, and professional approach to handling customer concerns.\r\n\r\nProblem-Solving: Ability to listen actively and identify solutions to customer issues efficiently.\r\n\r\nAvailability: Flexibility to work part-time hours, which may include occasional evenings or weekends.\r\n\r\nExperience: Previous experience in customer service or a call center environment is preferred but not required.', 'published', '2026-01-15', '2026-02-15', 1, 2, 0, NULL, '2026-01-15 07:59:59', NULL),
(6, 'Project Manager', 5, 'On-site', 1, 'Full-Time', 1, 'We are looking for a motivated and experienced Project Manager to plan, coordinate, and manage projects from initiation to completion. The ideal candidate will ensure projects are delivered on time, within scope, and according to requirements.\r\n\r\nKey Responsibilities\r\n\r\nPlan and manage project activities and timelines\r\n\r\nCoordinate tasks among team members\r\n\r\nMonitor project progress and prepare status reports\r\n\r\nIdentify risks and implement corrective actions\r\n\r\nCommunicate with stakeholders and management\r\n\r\nEnsure project objectives and deadlines are met', 'Strong leadership and communication skills\r\n\r\nGood time management and organizational skills\r\n\r\nAbility to manage multiple tasks simultaneously\r\n\r\nProblem-solving and decision-making abilities\r\n\r\nKnowledge of project management tools and methodologies\r\n\r\nBachelor’s degree in IT, Management, Business, or a related field\r\n\r\nProject Management certification is an added advantage', 'published', '2026-01-16', '2026-01-31', 1, 8, 0, NULL, '2026-01-16 21:56:59', NULL),
(7, 'Database Administrator', 1, 'Remote', 4, 'Part-Time', 1, 'We are seeking a skilled Database Administrator to manage, maintain, and secure organizational databases. The role involves ensuring data availability, performance optimization, backup and recovery, and supporting application teams with database-related requirements.', 'Bachelor’s degree in Information Technology, Computer Science, or a related field\r\n\r\nProven experience as a Database Administrator or in a similar role\r\n\r\nStrong knowledge of database management systems (MySQL, PostgreSQL, Oracle, or SQL Server)\r\n\r\nExperience with database installation, configuration, backup, and recovery\r\n\r\nKnowledge of data security, user access control, and performance tuning\r\n\r\nAbility to troubleshoot database issues and ensure high availability\r\n\r\nGood analytical and problem-solving skills\r\n\r\nStrong communication and documentation skills', 'draft', NULL, '2026-01-31', 1, 2, 0, NULL, '2026-01-16 22:05:01', NULL),
(8, 'Junior QA Engineer', 1, 'Hybrid', 1, 'Contract', 1, 'We are seeking a motivated Junior QA Engineer to support the testing of software applications and help ensure product quality. The role involves executing test cases, identifying bugs, and working closely with the development team to deliver reliable and user-friendly software solutions.', 'Degree or diploma in IT, Computer Science, or a related field\r\n\r\nBasic knowledge of software testing concepts\r\n\r\nFamiliarity with manual testing techniques\r\n\r\nUnderstanding of SDLC and Agile processes\r\n\r\nGood attention to detail and analytical skills\r\n\r\nWillingness to learn and grow in QA practices', 'published', '2026-01-17', '2026-02-28', 1, 2, 0, NULL, '2026-01-17 09:16:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_post_benefits`
--

DROP TABLE IF EXISTS `job_post_benefits`;
CREATE TABLE IF NOT EXISTS `job_post_benefits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_post_id` int(11) NOT NULL,
  `benefit_id` int(11) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `job_post_benefits__benefit_fk` (`benefit_id`),
  KEY `job_post_id` (`job_post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `job_post_benefits`
--

INSERT INTO `job_post_benefits` (`id`, `job_post_id`, `benefit_id`, `createdAt`) VALUES
(8, 5, 1, '2026-01-15 07:59:59'),
(9, 5, 4, '2026-01-15 07:59:59'),
(10, 5, 11, '2026-01-15 07:59:59'),
(11, 6, 1, '2026-01-16 21:56:59'),
(12, 6, 2, '2026-01-16 21:56:59'),
(13, 6, 4, '2026-01-16 21:56:59'),
(14, 6, 5, '2026-01-16 21:56:59'),
(15, 6, 8, '2026-01-16 21:56:59'),
(16, 6, 9, '2026-01-16 21:56:59'),
(17, 7, 1, '2026-01-16 22:05:01'),
(18, 7, 4, '2026-01-16 22:05:01'),
(19, 7, 7, '2026-01-16 22:05:01'),
(20, 7, 10, '2026-01-16 22:05:01'),
(21, 7, 11, '2026-01-16 22:05:01'),
(22, 8, 1, '2026-01-17 09:16:04'),
(23, 8, 2, '2026-01-17 09:16:04'),
(24, 8, 3, '2026-01-17 09:16:04'),
(25, 8, 6, '2026-01-17 09:16:04'),
(26, 8, 9, '2026-01-17 09:16:04'),
(36, 4, 1, '2026-01-18 03:24:01'),
(37, 4, 4, '2026-01-18 03:24:01'),
(38, 4, 7, '2026-01-18 03:24:01'),
(39, 4, 8, '2026-01-18 03:24:01'),
(40, 4, 9, '2026-01-18 03:24:01'),
(41, 4, 10, '2026-01-18 03:24:01'),
(42, 4, 12, '2026-01-18 03:24:01');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4;

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
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'receiver',
  `sender_id` int(11) NOT NULL COMMENT 'who triggered',
  `application_id` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `sender_id`, `application_id`, `type`, `message`, `is_read`, `created_at`) VALUES
(1, 8, 3, 1, 'NEW_APPLICATION', 'New application received for \'Project Manager\' from uoc user.', 1, '2026-01-16 22:12:04');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

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

DROP TABLE IF EXISTS `skills`;
CREATE TABLE IF NOT EXISTS `skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('Technical','Common') DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `createdAt` datetime NOT NULL,
  `updatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4;

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

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `login_count` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 - active | 0 - inactive',
  `is_deleted` tinyint(1) DEFAULT '0' COMMENT '1 - yes | 0 - no',
  `deletedAt` datetime DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  KEY `users_role_fk` (`role_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `role_id`, `first_name`, `last_name`, `profile_image`, `gender`, `company_id`, `last_login`, `login_count`, `status`, `is_deleted`, `deletedAt`, `createdAt`, `updatedAt`) VALUES
(1, 'admin@jobboardplus.lk', 'admin', '$2y$10$kc0pd1QyTypFOolv7ElkCe6OPgS1PiMfTkHTug3P0pGBfvymoQnzu', 3, 'Admin', 'User', 'assets/images/default_profile_image.png', 'Other', NULL, '2026-01-18 09:35:42', 4, 1, 0, NULL, '2026-01-11 21:21:43', NULL),
(2, 'jobs@abcsolutions.lk', 'employer', '$2y$10$kc0pd1QyTypFOolv7ElkCe6OPgS1PiMfTkHTug3P0pGBfvymoQnzu', 2, 'ABC Solutions', 'Recuiter', 'assets/images/default_profile_image.png', 'Other', 1, '2026-01-18 10:27:36', 3, 1, 0, NULL, '2026-01-11 21:22:46', NULL),
(3, 'jobseeker@example.com', 'uoc', '$2y$10$KmbK44xtP21FJYJZWT89M.FF7Y/gANtaDdCy6/KCoZ0fvcaxYETSO', 1, 'uoc', 'user', 'assets/images/default_profile_image.png', 'Other', NULL, '2026-01-18 09:51:58', 4, 1, 0, NULL, '2026-01-11 21:36:46', NULL),
(4, 'Gihan@gmail.com', 'Gihan', '$2y$10$W5qnY7o/.pokaEy3umHSfeqmuCHVwwOoocw6h26kPaAcqOtTcka8W', 1, 'Gihan', 'Udayanga', 'assets/images/default_profile_image.png', 'Male', NULL, '2026-01-15 06:24:11', 2, 1, 0, NULL, '2026-01-15 05:32:09', NULL),
(5, 'gayangitharaka.gt@gmail.com', 'gayangi', '$2y$10$2gitILLxaJ57nTTDdpYh0OA0/e7awc9iPK4ItouV/Cnwa2e/qGmoq', 2, 'Gayangi', 'Wijepala', 'assets/images/default_profile_image.png', 'Other', 2, '2026-01-16 07:47:02', 2, 1, 0, NULL, '2026-01-15 19:50:20', NULL),
(6, 'tw@gmail.com', 'tw', '$2y$10$0D4hPQNzunwN7U.L20PWc.rVxOUua7WtqQBNWJ/pahQZ/AMN5O94K', 2, 'Tharaka', 'Wijepala', 'assets/images/default_profile_image.png', 'Other', 3, '2026-01-16 07:51:34', 1, 1, 0, NULL, '2026-01-16 07:51:21', NULL),
(7, 'tharindu@123', 'Tharindu', '$2y$10$68ZNZBIPQZeN0lULLyG1SeGBNHuEoiAFNEToOA74c9u2tl5yMqnmW', 2, 'Roshan Tharindu', 'Wijepala', 'assets/images/default_profile_image.png', 'Other', 4, '2026-01-16 07:56:40', 1, 1, 0, NULL, '2026-01-16 07:54:43', NULL),
(8, 'nisal123@gmail.com', 'nisal1', '$2y$10$eSc207Eubeo28WNKNRorye/MP2Arr6GsTbnKzoI/XgosWjkRSY61y', 2, 'Nisal', 'Perera', 'assets/images/default_profile_image.png', 'Other', 5, '2026-01-16 21:09:55', 2, 1, 0, NULL, '2026-01-16 19:17:41', NULL),
(9, 'employer@gmail.com', 'employer1', '$2y$10$yzx6lep.34w3kjoSScwXiOKMdkOVHb.8X1rZQF8/JObQEQgF37Sc2', 2, 'employer1', 'last', 'assets/images/default_profile_image.png', 'Other', 7, '2026-01-18 09:18:29', 1, 1, 0, NULL, '2026-01-18 09:17:42', NULL),
(10, 'testqq@gmail.com', 'username', '$2y$10$vSwq9F/ZglaCuTBpoH403uMsi7x1IC9ovMP1Vgrydu6ZknxoLqj0i', 1, 'test', 'last', 'assets/images/default_profile_image.png', 'Other', NULL, '2026-01-18 09:27:53', 1, 1, 0, NULL, '2026-01-18 09:27:04', NULL),
(11, 'epic@gmail.com', 'epic', '$2y$10$1w84Y8XU2HuhuQs5PSqOb.RRNQSKuLHWMhLJO3Onps5qhAMiXDGEK', 2, '', '', 'assets/images/default_profile_image.png', 'Other', 8, NULL, 0, 1, 0, NULL, '2026-01-18 09:40:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_skills`
--

DROP TABLE IF EXISTS `user_skills`;
CREATE TABLE IF NOT EXISTS `user_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `skill_id` (`skill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_skills`
--

INSERT INTO `user_skills` (`id`, `user_id`, `skill_id`, `status`, `createdAt`, `updatedAt`) VALUES
(1, 3, 1, 1, '2026-01-11 21:56:44', '2026-01-11 21:56:44'),
(2, 3, 3, 1, '2026-01-11 21:56:44', '2026-01-11 21:56:44'),
(3, 3, 11, 1, '2026-01-11 21:56:44', '2026-01-11 21:56:44'),
(4, 3, 12, 1, '2026-01-11 21:56:44', '2026-01-11 21:56:44'),
(6, 3, 30, 1, '2026-01-11 21:56:44', '2026-01-11 21:56:44'),
(7, 10, 8, 1, '2026-01-18 09:27:53', NULL),
(8, 10, 12, 1, '2026-01-18 09:27:53', NULL);

--
-- Constraints for dumped tables
--

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
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
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
