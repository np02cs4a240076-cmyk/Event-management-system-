<?php
namespace App\Models;

require_once __DIR__ . '/../../db.php';

use Database;
use PDO;

class Attendee {
    private $db;
    private $table = 'attendees';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Find all attendees
     */
    public function findAll() {
        $stmt = $this->db->prepare("SELECT a.*, se.event_title, se.sport_name 
                                    FROM {$this->table} a 
                                    LEFT JOIN sport_events se ON a.sport_event_id = se.id 
                                    ORDER BY a.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Find attendee by ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT a.*, se.event_title, se.sport_name 
                                    FROM {$this->table} a 
                                    LEFT JOIN sport_events se ON a.sport_event_id = se.id 
                                    WHERE a.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Find attendees by event ID
     */
    public function findByEventId($eventId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE sport_event_id = ? ORDER BY created_at DESC");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }

    /**
     * Count attendees by event ID
     */
    public function countByEventId($eventId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE sport_event_id = ?");
        $stmt->execute([$eventId]);
        return $stmt->fetchColumn();
    }

    /**
     * Check if user already attending event
     */
    public function isAttending($eventId, $email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE sport_event_id = ? AND email = ?");
        $stmt->execute([$eventId, $email]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Create new attendee
     */
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} 
            (sport_event_id, user_id, full_name, email, phone) 
            VALUES (?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['sport_event_id'],
            $data['user_id'] ?? null,
            $data['full_name'],
            $data['email'],
            $data['phone'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }

    /**
     * Delete attendee
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get attendee count
     */
    public function count() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
