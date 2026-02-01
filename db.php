<?php


define('DB_HOST', 'localhost');
define('DB_NAME', 'events');
define('DB_USER', 'root');
define('DB_PASS', '');

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Initialize database and create tables if not exist
function initializeDatabase() {
    try {
        // First connect without database to create it
        $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create database if not exists
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `" . DB_NAME . "`");

        // Create users table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(255) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `role` ENUM('admin', 'user') DEFAULT 'user',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Create sport_events table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `sport_events` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Create participants table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `participants` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `sport_event_id` INT NOT NULL,
            `student_name` VARCHAR(100) NOT NULL,
            `student_email` VARCHAR(255) NOT NULL,
            `phone` VARCHAR(20),
            `team_name` VARCHAR(100),
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`sport_event_id`) REFERENCES `sport_events`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Create attendees table for event attendance
        $pdo->exec("CREATE TABLE IF NOT EXISTS `attendees` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `sport_event_id` INT NOT NULL,
            `user_id` INT,
            `full_name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `phone` VARCHAR(20),
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`sport_event_id`) REFERENCES `sport_events`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        return true;
    } catch (PDOException $e) {
        die("Database initialization failed: " . $e->getMessage());
    }
}

// Run initialization
initializeDatabase();
