<?php
namespace App\Controllers;

use App\Models\SportEvent;
use App\Models\Participant;
use Src\View;
use Src\Session;

class SportEventController {
    private $eventModel;
    private $participantModel;
    private $view;

    public function __construct() {
        $this->eventModel = new SportEvent();
        $this->participantModel = new Participant();
        $this->view = View::getInstance();
    }

    /**
     * Display all events
     */
    public function index() {
        $events = $this->eventModel->findAll();
        $sports = $this->eventModel->getDistinctSports();
        
        echo $this->view->render('sports.index', [
            'events' => $events,
            'sports' => $sports
        ]);
    }

    /**
     * Show create event form
     */
    public function create() {
        echo $this->view->render('sports.create');
    }

    /**
     * Store new event
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=sports.create');
            exit;
        }

        // Verify CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Session::verifyCsrf($csrfToken)) {
            Session::flash('error', 'Invalid security token. Please try again.');
            header('Location: index.php?route=sports.create');
            exit;
        }

        // Validate inputs
        $errors = $this->validateEventData($_POST);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('error', 'Please correct the errors below.');
            header('Location: index.php?route=sports.create');
            exit;
        }

        // Create event
        try {
            // Get user ID and ensure it exists; fallback to null if invalid
            $userId = Session::userId();
            if ($userId) {
                $userModel = new \App\Models\User();
                $existingUser = $userModel->findById($userId);
                if (!$existingUser) {
                    $userId = null;
                }
            }

            $eventId = $this->eventModel->create([
                'sport_name' => trim($_POST['sport_name']),
                'event_title' => trim($_POST['event_title']),
                'venue' => trim($_POST['venue']),
                'event_date' => $_POST['event_date'],
                'event_time' => $_POST['event_time'] ?? '09:00:00',
                'team_limit' => intval($_POST['team_limit'] ?? 10),
                'total_capacity' => intval($_POST['total_capacity'] ?? 100),
                'description' => trim($_POST['description'] ?? ''),
                'status' => $_POST['status'] ?? 'upcoming',
                'created_by' => $userId ?: null
            ]);

            Session::flash('success', 'Event created successfully!');
            header('Location: index.php?route=sports.show&id=' . $eventId);
            exit;
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to create event: ' . $e->getMessage());
            header('Location: index.php?route=sports.create');
            exit;
        }
    }

    /**
     * Show single event
     */
    public function show() {
        $id = intval($_GET['id'] ?? 0);
        
        if (!$id) {
            Session::flash('error', 'Event not found.');
            header('Location: index.php?route=sports.index');
            exit;
        }

        $event = $this->eventModel->findById($id);
        
        if (!$event) {
            Session::flash('error', 'Event not found.');
            header('Location: index.php?route=sports.index');
            exit;
        }

        $participants = $this->participantModel->findByEventId($id);
        $teams = $this->participantModel->getTeamsByEventId($id);
        $availableSeats = $this->eventModel->getAvailableSeats($id);

        echo $this->view->render('sports.show', [
            'event' => $event,
            'participants' => $participants,
            'teams' => $teams,
            'availableSeats' => $availableSeats
        ]);
    }

    /**
     * Show edit form
     */
    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        
        if (!$id) {
            Session::flash('error', 'Event not found.');
            header('Location: index.php?route=sports.index');
            exit;
        }

        $event = $this->eventModel->findById($id);
        
        if (!$event) {
            Session::flash('error', 'Event not found.');
            header('Location: index.php?route=sports.index');
            exit;
        }

        echo $this->view->render('sports.edit', ['event' => $event]);
    }

    /**
     * Update event
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=sports.index');
            exit;
        }

        $id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

        if (!$id) {
            Session::flash('error', 'Event not found.');
            header('Location: index.php?route=sports.index');
            exit;
        }

        // Verify CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Session::verifyCsrf($csrfToken)) {
            Session::flash('error', 'Invalid security token. Please try again.');
            header('Location: index.php?route=sports.edit&id=' . $id);
            exit;
        }

        // Validate inputs
        $errors = $this->validateEventData($_POST);

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('error', 'Please correct the errors below.');
            header('Location: index.php?route=sports.edit&id=' . $id);
            exit;
        }

        // Update event
        try {
            $this->eventModel->update($id, [
                'sport_name' => trim($_POST['sport_name']),
                'event_title' => trim($_POST['event_title']),
                'venue' => trim($_POST['venue']),
                'event_date' => $_POST['event_date'],
                'event_time' => $_POST['event_time'] ?? '09:00:00',
                'team_limit' => intval($_POST['team_limit'] ?? 10),
                'total_capacity' => intval($_POST['total_capacity'] ?? 100),
                'description' => trim($_POST['description'] ?? ''),
                'status' => $_POST['status'] ?? 'upcoming'
            ]);

            Session::flash('success', 'Event updated successfully!');
            header('Location: index.php?route=sports.show&id=' . $id);
            exit;
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to update event. Please try again.');
            header('Location: index.php?route=sports.edit&id=' . $id);
            exit;
        }
    }

    /**
     * Delete event
     */
    public function delete() {
        $id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

        if (!$id) {
            Session::flash('error', 'Event not found.');
            header('Location: index.php?route=sports.index');
            exit;
        }

        // Verify CSRF token for POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (!Session::verifyCsrf($csrfToken)) {
                Session::flash('error', 'Invalid security token. Please try again.');
                header('Location: index.php?route=sports.index');
                exit;
            }
        }

        try {
            $this->eventModel->delete($id);
            Session::flash('success', 'Event deleted successfully!');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to delete event. Please try again.');
        }

        header('Location: index.php?route=sports.index');
        exit;
    }

    /**
     * Search events (Ajax)
     */
    public function search() {
        header('Content-Type: application/json');
        
        $query = trim($_GET['q'] ?? $_POST['q'] ?? '');
        
        if (empty($query)) {
            echo json_encode(['success' => true, 'events' => $this->eventModel->findAll()]);
            exit;
        }

        $events = $this->eventModel->search($query);
        
        echo json_encode([
            'success' => true,
            'events' => $events,
            'count' => count($events),
            'query' => htmlspecialchars($query)
        ]);
        exit;
    }

    /**
     * Attend section - show all events for attendance
     */
    public function attend() {
        $events = $this->eventModel->findAll();
        
        echo $this->view->render('sports.attend', [
            'events' => $events
        ]);
    }

    /**
     * Validate event data
     */
    private function validateEventData($data) {
        $errors = [];

        if (empty(trim($data['sport_name'] ?? ''))) {
            $errors['sport_name'] = 'Sport name is required';
        }

        if (empty(trim($data['event_title'] ?? ''))) {
            $errors['event_title'] = 'Event title is required';
        }

        if (empty(trim($data['venue'] ?? ''))) {
            $errors['venue'] = 'Venue is required';
        }

        if (empty($data['event_date'] ?? '')) {
            $errors['event_date'] = 'Event date is required';
        } elseif (strtotime($data['event_date']) < strtotime('today')) {
            $errors['event_date'] = 'Event date cannot be in the past';
        }

        if (isset($data['team_limit']) && intval($data['team_limit']) < 1) {
            $errors['team_limit'] = 'Team limit must be at least 1';
        }

        if (isset($data['total_capacity']) && intval($data['total_capacity']) < 1) {
            $errors['total_capacity'] = 'Total capacity must be at least 1';
        }

        return $errors;
    }
}
