<?php
/**
 * Workshop 8 - College Sports Event Management System
 * Public Entry Point / Front Controller
 * All requests are routed through this file
 */

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Autoload Composer dependencies
require_once BASE_PATH . '/vendor/autoload.php';

// Load database configuration
require_once BASE_PATH . '/db.php';

// Load helper functions (must be loaded before views)
require_once BASE_PATH . '/src/helpers.php';

// Load source files
require_once BASE_PATH . '/src/Session.php';
require_once BASE_PATH . '/src/View.php';
require_once BASE_PATH . '/src/Router.php';

// Load Models
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/models/SportEvent.php';
require_once BASE_PATH . '/app/models/Participant.php';
require_once BASE_PATH . '/app/models/Attendee.php';

// Load Controllers
require_once BASE_PATH . '/app/controllers/AuthController.php';
require_once BASE_PATH . '/app/controllers/SportEventController.php';
require_once BASE_PATH . '/app/controllers/ParticipantController.php';

use Src\Session;
use Src\Router;
use App\Controllers\AuthController;
use App\Controllers\SportEventController;
use App\Controllers\ParticipantController;

// Start session
Session::start();

// Initialize Router
$router = new Router();

// ============================================
// Define Routes
// ============================================

// Auth Routes (Public)
$router->addRoute('auth.login', AuthController::class, 'login', false);
$router->addRoute('auth.authenticate', AuthController::class, 'authenticate', false);
$router->addRoute('auth.register', AuthController::class, 'register', false);
$router->addRoute('auth.store', AuthController::class, 'store', false);
$router->addRoute('auth.logout', AuthController::class, 'logout', false);
$router->addRoute('auth.checkEmail', AuthController::class, 'checkEmail', false);

// Auth Routes (Protected)
$router->addRoute('auth.dashboard', AuthController::class, 'dashboard', true);

// Sports Events Routes (Public - View)
$router->addRoute('sports.index', SportEventController::class, 'index', false);
$router->addRoute('sports.show', SportEventController::class, 'show', false);
$router->addRoute('sports.search', SportEventController::class, 'search', false);

// Sports Events Routes (Protected - CRUD)
$router->addRoute('sports.create', SportEventController::class, 'create', true);
$router->addRoute('sports.store', SportEventController::class, 'store', true);
$router->addRoute('sports.edit', SportEventController::class, 'edit', true);
$router->addRoute('sports.update', SportEventController::class, 'update', true);
$router->addRoute('sports.delete', SportEventController::class, 'delete', true);

// Participants Routes (Public - Registration)
$router->addRoute('participants.register', ParticipantController::class, 'register', false);
$router->addRoute('participants.store', ParticipantController::class, 'store', false);
$router->addRoute('participants.checkRegistration', ParticipantController::class, 'checkRegistration', false);

// Participants Routes (Protected)
$router->addRoute('participants.delete', ParticipantController::class, 'delete', true);

// ============================================
// Dispatch Route
// ============================================

// Get the requested route
$route = $_GET['route'] ?? 'sports.index';

// Clean the route
$route = preg_replace('/[^a-zA-Z0-9._-]/', '', $route);

// Dispatch the route
$router->dispatch($route);
