-- =====================================================
-- Workshop 8 - College Sports Event Management System
-- Database Schema
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS `events` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `events`;

-- =====================================================
-- USERS TABLE
-- Stores user accounts for authentication
-- =====================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'user') DEFAULT 'user',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SPORT EVENTS TABLE
-- Stores all sports events information
-- =====================================================
CREATE TABLE IF NOT EXISTS `sport_events` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sport_name` VARCHAR(100) NOT NULL,
    `event_title` VARCHAR(255) NOT NULL,
    `venue` VARCHAR(255) NOT NULL,
    `event_date` DATE NOT NULL,
    `event_time` TIME DEFAULT '09:00:00',
    `team_limit` INT DEFAULT 10,
    `total_capacity` INT DEFAULT 100,
    `booking_count` INT DEFAULT 0,
    `description` TEXT,
    `status` ENUM('upcoming', 'ongoing', 'completed', 'cancelled') DEFAULT 'upcoming',
    `created_by` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- PARTICIPANTS TABLE
-- Stores event registrations/participants
-- =====================================================
CREATE TABLE IF NOT EXISTS `participants` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `sport_event_id` INT NOT NULL,
    `student_name` VARCHAR(100) NOT NULL,
    `student_email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20),
    `team_name` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`sport_event_id`) REFERENCES `sport_events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- INDEXES FOR BETTER PERFORMANCE
-- =====================================================
CREATE INDEX idx_events_date ON `sport_events`(`event_date`);
CREATE INDEX idx_events_status ON `sport_events`(`status`);
CREATE INDEX idx_events_sport ON `sport_events`(`sport_name`);
CREATE INDEX idx_participants_event ON `participants`(`sport_event_id`);

-- =====================================================
-- SAMPLE DATA (Optional - Uncomment to use)
-- =====================================================

/*
-- Sample Admin User (password: admin123)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Admin User', 'admin@college.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Sample Regular User (password: password123)
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('John Student', 'john@college.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Sample Events
INSERT INTO `sport_events` (`sport_name`, `event_title`, `venue`, `event_date`, `event_time`, `team_limit`, `total_capacity`, `description`, `status`, `created_by`) VALUES
('Football', 'Inter-College Football Championship', 'Main Stadium', '2026-03-15', '14:00:00', 16, 200, 'Annual inter-college football tournament', 'upcoming', 1),
('Basketball', 'Basketball League 2026', 'Indoor Sports Complex', '2026-03-20', '10:00:00', 8, 100, 'College basketball league matches', 'upcoming', 1),
('Cricket', 'Cricket Premier League', 'Cricket Ground', '2026-04-01', '09:00:00', 12, 150, 'T20 cricket tournament', 'upcoming', 1),
('Volleyball', 'Volleyball Tournament', 'Outdoor Courts', '2026-03-25', '11:00:00', 10, 80, 'Mixed volleyball competition', 'upcoming', 1),
('Athletics', 'Annual Sports Meet', 'Athletic Track', '2026-04-10', '08:00:00', 20, 300, 'Track and field events', 'upcoming', 1);
*/
