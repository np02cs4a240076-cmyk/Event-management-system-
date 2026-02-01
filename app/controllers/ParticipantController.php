<?php
namespace App\Controllers;

use App\Models\Participant;
use App\Models\SportEvent;
use App\Models\Attendee;
use Src\View;
use Src\Session;

class ParticipantController {
    private $participantModel;
    private $eventModel;
    private $attendeeModel;
    private $view;

    public function __construct() {
        $this->participantModel = new Participant();
        $this->eventModel = new SportEvent();
        $this->attendeeModel = new Attendee();
        $this->view = View::getInstance();
    }

    /**
     * Show registration form
     */
    public function register() {
        $eventId = intval($_GET['event_id'] ?? 0);
        
        $event = null;
        if ($eventId) {
            $event = $this->eventModel->findById($eventId);
        }

        $events = $this->eventModel->findUpcoming();
        
        echo $this->view->render('participants.register', [
            'event' => $event,
            'events' => $events
        ]);
    }

    /**
     * Store participant registration
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=participants.register');
            exit;
        }

        // Verify CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Session::verifyCsrf($csrfToken)) {
            Session::flash('error', 'Invalid security token. Please try again.');
            header('Location: index.php?route=participants.register');
            exit;
        }

        $eventId = intval($_POST['sport_event_id'] ?? 0);
        $studentName = trim($_POST['student_name'] ?? '');
        $studentEmail = trim($_POST['student_email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $teamName = trim($_POST['team_name'] ?? '');

        // Validate inputs
        $errors = [];

        if (!$eventId) {
            $errors['sport_event_id'] = 'Please select an event';
        } else {
            $event = $this->eventModel->findById($eventId);
            if (!$event) {
                $errors['sport_event_id'] = 'Invalid event selected';
            } elseif ($event['status'] !== 'upcoming') {
                $errors['sport_event_id'] = 'This event is no longer accepting registrations';
            }
        }

        if (empty($studentName)) {
            $errors['student_name'] = 'Name is required';
        } elseif (strlen($studentName) < 2) {
            $errors['student_name'] = 'Name must be at least 2 characters';
        }

        if (empty($studentEmail)) {
            $errors['student_email'] = 'Email is required';
        } elseif (!filter_var($studentEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['student_email'] = 'Invalid email format';
        } elseif ($eventId && $this->participantModel->isRegistered($eventId, $studentEmail)) {
            $errors['student_email'] = 'This email is already registered for this event';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('error', 'Please correct the errors below.');
            $redirectUrl = $eventId ? "index.php?route=participants.register&event_id={$eventId}" : "index.php?route=participants.register";
            header('Location: ' . $redirectUrl);
            exit;
        }

        // Check team limit
        if (!empty($teamName)) {
            $currentTeams = $this->participantModel->countTeamsByEventId($eventId);
            if ($currentTeams >= $event['team_limit']) {
                Session::flash('error', 'Maximum team limit reached for this event.');
                header('Location: index.php?route=participants.register&event_id=' . $eventId);
                exit;
            }
        }

        // Create participant
        try {
            $participantId = $this->participantModel->create([
                'sport_event_id' => $eventId,
                'student_name' => $studentName,
                'student_email' => $studentEmail,
                'phone' => $phone,
                'team_name' => $teamName
            ]);

            // Increment booking count
            $this->eventModel->incrementBookingCount($eventId);

            Session::flash('success', 'Registration successful! You have been registered for the event.');
            header('Location: index.php?route=sports.show&id=' . $eventId);
            exit;
        } catch (\Exception $e) {
            Session::flash('error', 'Registration failed. Please try again.');
            header('Location: index.php?route=participants.register&event_id=' . $eventId);
            exit;
        }
    }

    /**
     * Delete participant
     */
    public function delete() {
        $id = intval($_GET['id'] ?? $_POST['id'] ?? 0);

        if (!$id) {
            Session::flash('error', 'Participant not found.');
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

        $participant = $this->participantModel->findById($id);
        $eventId = $participant['sport_event_id'] ?? 0;

        try {
            $this->participantModel->delete($id);
            
            // Decrement booking count
            if ($eventId) {
                $this->eventModel->decrementBookingCount($eventId);
            }
            
            Session::flash('success', 'Participant removed successfully!');
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to remove participant. Please try again.');
        }

        $redirectUrl = $eventId ? "index.php?route=sports.show&id={$eventId}" : "index.php?route=sports.index";
        header('Location: ' . $redirectUrl);
        exit;
    }

    /**
     * Attend event
     */
    public function attend() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=sports.attend');
            exit;
        }

        // Verify CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Session::verifyCsrf($csrfToken)) {
            Session::flash('error', 'Invalid security token. Please try again.');
            header('Location: index.php?route=sports.attend');
            exit;
        }

        $eventId = intval($_POST['sport_event_id'] ?? 0);
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        // Validate inputs
        $errors = [];

        if (!$eventId) {
            $errors['sport_event_id'] = 'Please select an event';
        } else {
            $event = $this->eventModel->findById($eventId);
            if (!$event) {
                $errors['sport_event_id'] = 'Invalid event selected';
            } elseif (!$this->eventModel->hasCapacity($eventId)) {
                $errors['sport_event_id'] = 'This event is fully booked';
            }
        }

        if (empty($fullName)) {
            $errors['full_name'] = 'Full name is required';
        }

        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        } elseif ($eventId && $this->attendeeModel->isAttending($eventId, $email)) {
            $errors['email'] = 'You are already attending this event';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('error', 'Please correct the errors below.');
            header('Location: index.php?route=sports.attend');
            exit;
        }

        // Create attendee
        try {
            $attendeeId = $this->attendeeModel->create([
                'sport_event_id' => $eventId,
                'user_id' => Session::userId(),
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone
            ]);

            // Increment booking count
            $this->eventModel->incrementBookingCount($eventId);

            Session::flash('success', 'You have successfully registered to attend this event!');
            header('Location: index.php?route=sports.attend');
            exit;
        } catch (\Exception $e) {
            Session::flash('error', 'Failed to register attendance. Please try again.');
            header('Location: index.php?route=sports.attend');
            exit;
        }
    }

    /**
     * Check if email is registered for event (Ajax)
     */
    public function checkRegistration() {
        header('Content-Type: application/json');
        
        $eventId = intval($_GET['event_id'] ?? 0);
        $email = trim($_GET['email'] ?? '');
        
        if (!$eventId || empty($email)) {
            echo json_encode(['registered' => false]);
            exit;
        }

        $isRegistered = $this->participantModel->isRegistered($eventId, $email);
        
        echo json_encode([
            'registered' => $isRegistered,
            'message' => $isRegistered ? 'Already registered for this event' : 'Not registered'
        ]);
        exit;
    }
}
