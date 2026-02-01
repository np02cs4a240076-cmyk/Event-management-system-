<?php
namespace App\Models;

require_once __DIR__ . '/../../db.php';

use Database;
use PDO;

class SportEvent {
    private $db;
    private $table = 'sport_events';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Find all sport events
     */
    public function findAll($limit = null, $offset = 0) {
        $sql = "SELECT se.*, u.name as creator_name 
                FROM {$this->table} se 
                LEFT JOIN users u ON se.created_by = u.id 
                ORDER BY se.event_date ASC, se.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$offset}, {$limit}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Find upcoming events
     */
    public function findUpcoming($limit = null) {
        $sql = "SELECT se.*, u.name as creator_name 
                FROM {$this->table} se 
                LEFT JOIN users u ON se.created_by = u.id 
                WHERE se.event_date >= CURDATE() AND se.status = 'upcoming'
                ORDER BY se.event_date ASC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Find event by ID
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT se.*, u.name as creator_name 
                                    FROM {$this->table} se 
                                    LEFT JOIN users u ON se.created_by = u.id 
                                    WHERE se.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Create new sport event
     */
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} 
            (sport_name, event_title, venue, event_date, event_time, team_limit, total_capacity, description, status, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data['sport_name'],
            $data['event_title'],
            $data['venue'],
            $data['event_date'],
            $data['event_time'] ?? '09:00:00',
            $data['team_limit'] ?? 10,
            $data['total_capacity'] ?? 100,
            $data['description'] ?? null,
            $data['status'] ?? 'upcoming',
            $data['created_by'] ?? null
        ]);
        
        return $this->db->lastInsertId();
    }

    /**
     * Update sport event
     */
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        $allowedFields = ['sport_name', 'event_title', 'venue', 'event_date', 'event_time', 
                          'team_limit', 'total_capacity', 'description', 'status', 'booking_count'];
        
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
     * Delete sport event
     */
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Search events
     */
    public function search($query) {
        $searchTerm = "%{$query}%";
        $stmt = $this->db->prepare("SELECT se.*, u.name as creator_name 
                                    FROM {$this->table} se 
                                    LEFT JOIN users u ON se.created_by = u.id 
                                    WHERE se.sport_name LIKE ? 
                                    OR se.event_title LIKE ? 
                                    OR se.venue LIKE ?
                                    ORDER BY se.event_date ASC");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    /**
     * Get events by sport name
     */
    public function findBySportName($sportName) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE sport_name = ? ORDER BY event_date ASC");
        $stmt->execute([$sportName]);
        return $stmt->fetchAll();
    }

    /**
     * Get event count
     */
    public function count() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Get upcoming events count
     */
    public function countUpcoming() {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE event_date >= CURDATE() AND status = 'upcoming'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Increment booking count
     */
    public function incrementBookingCount($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET booking_count = booking_count + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Decrement booking count
     */
    public function decrementBookingCount($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET booking_count = GREATEST(0, booking_count - 1) WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get available seats
     */
    public function getAvailableSeats($id) {
        $event = $this->findById($id);
        if ($event) {
            return max(0, $event['total_capacity'] - $event['booking_count']);
        }
        return 0;
    }

    /**
     * Check if event has capacity
     */
    public function hasCapacity($id) {
        return $this->getAvailableSeats($id) > 0;
    }

    /**
     * Get distinct sport names
     */
    public function getDistinctSports() {
        $stmt = $this->db->prepare("SELECT DISTINCT sport_name FROM {$this->table} ORDER BY sport_name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
