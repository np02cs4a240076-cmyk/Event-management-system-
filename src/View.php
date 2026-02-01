<?php
namespace Src;

class View {
    private static $instance = null;
    private $viewsPath;
    private $data = [];

    private function __construct() {
        $this->viewsPath = dirname(__DIR__) . '/app/views';
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function render($view, $data = []) {
        // Add CSRF token to all views
        if (!isset($data['csrf_token'])) {
            $data['csrf_token'] = $_SESSION['csrf_token'] ?? '';
        }
        
        // Add auth user to all views
        $data['auth_user'] = $_SESSION['user'] ?? null;
        
        // Add flash messages
        $data['success'] = $_SESSION['success'] ?? null;
        $data['error'] = $_SESSION['error'] ?? null;
        $data['errors'] = $_SESSION['errors'] ?? [];
        
        // Clear flash messages after reading
        unset($_SESSION['success'], $_SESSION['error'], $_SESSION['errors']);
        
        // Convert view name to path (e.g., 'sports.index' to 'sports/index.php')
        $viewFile = $this->viewsPath . '/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View file not found: {$viewFile}");
        }
        
        // Extract data to variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        include $viewFile;
        
        // Get the content and clean the buffer
        return ob_get_clean();
    }
}
