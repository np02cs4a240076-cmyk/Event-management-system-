<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\SportEvent;
use App\Models\Participant;
use Src\View;
use Src\Session;

class AuthController {
    private $userModel;
    private $view;

    public function __construct() {
        $this->userModel = new User();
        $this->view = View::getInstance();
    }

    /**
     * Show login form
     */
    public function login() {
        if (Session::isLoggedIn()) {
            header('Location: index.php?route=auth.dashboard');
            exit;
        }
        echo $this->view->render('auth.login');
    }

    /**
     * Handle login authentication
     */
    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=auth.login');
            exit;
        }

        // Verify CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Session::verifyCsrf($csrfToken)) {
            Session::flash('error', 'Invalid security token. Please try again.');
            header('Location: index.php?route=auth.login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate inputs
        $errors = [];
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('error', 'Please correct the errors below.');
            header('Location: index.php?route=auth.login');
            exit;
        }

        // Verify credentials
        $user = $this->userModel->verifyPassword($email, $password);
        
        if ($user) {
            // Set session
            Session::set('user', $user);
            Session::regenerateCsrf();
            Session::flash('success', 'Welcome back, ' . htmlspecialchars($user['name']) . '!');
            header('Location: index.php?route=auth.dashboard');
            exit;
        } else {
            Session::flash('error', 'Invalid email or password.');
            header('Location: index.php?route=auth.login');
            exit;
        }
    }

    /**
     * Show registration form
     */
    public function register() {
        if (Session::isLoggedIn()) {
            header('Location: index.php?route=auth.dashboard');
            exit;
        }
        echo $this->view->render('auth.register');
    }

    /**
     * Handle user registration
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=auth.register');
            exit;
        }

        // Verify CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!Session::verifyCsrf($csrfToken)) {
            Session::flash('error', 'Invalid security token. Please try again.');
            header('Location: index.php?route=auth.register');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate inputs
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Name is required';
        } elseif (strlen($name) < 2) {
            $errors['name'] = 'Name must be at least 2 characters';
        }

        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        } elseif ($this->userModel->emailExists($email)) {
            $errors['email'] = 'Email already registered';
        }

        if (empty($password)) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('error', 'Please correct the errors below.');
            header('Location: index.php?route=auth.register');
            exit;
        }

        // Create user
        try {
            $userId = $this->userModel->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => 'user'
            ]);

            // Auto login after registration
            $user = $this->userModel->findById($userId);
            Session::set('user', $user);
            Session::regenerateCsrf();
            Session::flash('success', 'Account created successfully! Welcome, ' . htmlspecialchars($name) . '!');
            header('Location: index.php?route=auth.dashboard');
            exit;
        } catch (\Exception $e) {
            Session::flash('error', 'Registration failed. Please try again.');
            header('Location: index.php?route=auth.register');
            exit;
        }
    }

    /**
     * Show dashboard
     */
    public function dashboard() {
        $eventModel = new SportEvent();
        $participantModel = new Participant();

        $stats = [
            'total_events' => $eventModel->count(),
            'upcoming_events' => $eventModel->countUpcoming(),
            'total_participants' => $participantModel->count(),
            'total_users' => $this->userModel->count()
        ];

        $upcomingEvents = $eventModel->findUpcoming(5);
        $recentEvents = $eventModel->findAll(5);

        echo $this->view->render('auth.dashboard', [
            'stats' => $stats,
            'upcomingEvents' => $upcomingEvents,
            'recentEvents' => $recentEvents
        ]);
    }

    /**
     * Handle logout
     */
    public function logout() {
        Session::destroy();
        session_start();
        Session::flash('success', 'You have been logged out successfully.');
        header('Location: index.php?route=auth.login');
        exit;
    }

    /**
     * Check email uniqueness (Ajax)
     */
    public function checkEmail() {
        header('Content-Type: application/json');
        
        $email = trim($_GET['email'] ?? '');
        
        if (empty($email)) {
            echo json_encode(['available' => false, 'message' => 'Email is required']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['available' => false, 'message' => 'Invalid email format']);
            exit;
        }

        $exists = $this->userModel->emailExists($email);
        
        echo json_encode([
            'available' => !$exists,
            'message' => $exists ? 'Email already registered' : 'Email is available'
        ]);
        exit;
    }
}
