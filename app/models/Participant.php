<?php
namespace App\Models;

require_once __DIR__ . '/../../db.php';

use Database;
use PDO;

class Participant {
    private $db;
    private $table = 'participants';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Find all participants
     */
    public function findAll() {
        $stmt = $this->db->prepare("SELECT p.*, se.event_title, se.sport_name 
                                    FROM {$this->table} p 
                                    LEFT JOIN sport_events se ON p.sport_event_id = se.id 
                                    ORDER BY p.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Find participant by ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT p.*, se.event_title, se.sport_name 
                                    FROM {$this->table} p 
                                    LEFT JOIN sport_events se ON p.sport_event_id = se.id 
                                    WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Find participants by event ID
     */
    public function findByEventId($eventId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE sport_event_id = ? ORDER BY created_at DESC");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }

    /**
     * Count participants by event ID
     */
    public function countByEventId($eventId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE sport_event_id = ?");
        $stmt->execute([$eventId]);
        return $stmt->fetchColumn();
    }

    /**
     * Check if email already registered for event
     */
    public function isRegistered($eventId, $email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE sport_event_id = ? AND student_email = ?");
        $stmt->execute([$eventId, $email]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Create new participant
     */
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} 
            (sport_event_id, student_name, student_email, phone, team_name) 
            VALUES (?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['sport_event_id'],
            $data['student_name'],
            $data['student_email'],
            $data['phone'] ?? null,
            $data['team_name'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }

    /**
     * Update participant
     */
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        $allowedFields = ['student_name', 'student_email', 'phone', 'team_name'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "{$field} = ?";
                $values[] = $data[$field];
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $values[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($values);
    }

    /**
     * Delete participant
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get participant count
     */
    public function count() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Get teams by event ID
     */
    public function getTeamsByEventId($eventId) {
        $stmt = $this->db->prepare("SELECT DISTINCT team_name FROM {$this->table} WHERE sport_event_id = ? AND team_name IS NOT NULL AND team_name != '' ORDER BY team_name ASC");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Count teams by event ID
     */
    public function countTeamsByEventId($eventId) {
        $stmt = $this->db->prepare("SELECT COUNT(DISTINCT team_name) FROM {$this->table} WHERE sport_event_id = ? AND team_name IS NOT NULL AND team_name != ''");
        $stmt->execute([$eventId]);
        return $stmt->fetchColumn();
    }
}
