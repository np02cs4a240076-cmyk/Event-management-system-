<?php
namespace Src;

class Router {
    private $routes = [];
    private $middleware = [];

    public function addRoute($name, $controller, $method, $auth = false) {
        $this->routes[$name] = [
            'controller' => $controller,
            'method' => $method,
            'auth' => $auth
        ];
    }

    public function dispatch($routeName) {
        if (!isset($this->routes[$routeName])) {
            http_response_code(404);
            echo $this->render404();
            return;
        }

        $route = $this->routes[$routeName];

        // Check authentication if required
        if ($route['auth'] && !Session::isLoggedIn()) {
            Session::flash('error', 'Please login to access this page.');
            header('Location: index.php?route=auth.login');
            exit;
        }

        $controllerClass = $route['controller'];
        $method = $route['method'];

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo "Controller not found: {$controllerClass}";
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo "Method not found: {$method}";
            return;
        }

        return $controller->$method();
    }

    private function render404() {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <title>404 - Page Not Found</title>
            <style>
                body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
                .container { text-align: center; color: white; }
                h1 { font-size: 120px; margin: 0; }
                p { font-size: 24px; }
                a { color: white; text-decoration: none; background: rgba(255,255,255,0.2); padding: 10px 30px; border-radius: 25px; transition: all 0.3s; }
                a:hover { background: rgba(255,255,255,0.3); }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>404</h1>
                <p>Page Not Found</p>
                <a href="index.php?route=sports.index">Go Home</a>
            </div>
        </body>
        </html>';
    }
}
