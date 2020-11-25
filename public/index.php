<?php

use Site\Logger;

require '../Autoloader.php';

// Error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Our 'src' dir
define('SRC_DIR', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR);

// NOTE: It's better to implement and PSR messaging standart (request, response, etc.)

// Register autoloader
$autoloader = new Autoloader();
$autoloader->register();

/**
 * Storage path helper
 *
 * @param string $path
 * @return string
 */
function storage_path($path = '')
{
    return __DIR__ . '/../storage/app/' . $path;
}

/**
 * Get namespace classes
 *
 * @param $namespace
 * @return string[]
 */
function get_namespace_classes($namespace)
{
    $files = scandir(SRC_DIR . str_replace('\\', '/', $namespace));

    // We don't remove special files (such as "." and ".."). Because it's not classes and can't be passed thru class_exists
    $classes = array_map(function($file) use ($namespace) {
        return $namespace . '\\' . str_replace('.php', '', $file);
    }, $files);

    return array_filter($classes, function($possibleClass) {
        return class_exists($possibleClass);
    });
}

// Dynamically load all controllers
$controllers = [];

foreach (get_namespace_classes('Site\Controllers') as $controller) {
    $instance = new $controller;
    $r = (new ReflectionClass($instance))->getShortName();
    $controllers[$r] = $instance;
}

// Query string support
$url = strtok($_SERVER['REQUEST_URI'], '?');

// Register routes
$routes = [
    '/get' => [
        'action' => 'User@getAction',
        'method' => 'get' // There can be an array. Example: ['get', 'post']
    ],
    '/post' => [
        'action' => 'User@postAction',
        'method' => 'post'
    ],
    '/delete' => [
        'action' => 'User@deleteAction',
        'method' => 'delete'
    ]
];

foreach ($routes as $route => $data) {
    // NOTE: We can improve matching using preg_match
    if ($route == $url) {

        // Check method
        $method = isset($data['method']) ? $data['method'] : 'GET';
        if (is_array($method)) {
            $method = array_map('strtoupper', $method);
            if (!in_array($_SERVER['REQUEST_METHOD'], $method))
                continue; // Skip route
        } else if (strtoupper($method) != $_SERVER['REQUEST_METHOD']) {
            // Skip route
            continue;
        }

        // Can be simplified by moving to middleware
        if (!isset($data['action'])) {
            Logger::log(sprintf('Route %s matched. But invalid action (%s) found', $route, $data['action']));
            continue;
        }

        $routeAction = explode('@', @$data['action']);

        // Check for invalid data
        if (count($routeAction) != 2) {
            Logger::log(sprintf('Route %s matched. But invalid action (%s) found', $route, $data['action']));
            continue;
        }

        $actionClass = $routeAction[0];
        $actionFunction = $routeAction[1];

        if (
            isset($controllers[$actionClass])
            && method_exists($controllers[$actionClass], $actionFunction)
        ) {
            Logger::log(sprintf('Route %s matched. Action %s found', $route, $data['action']));
            echo $controllers[$actionClass]->{$actionFunction}();
            return;
        }
    }

}

// No one route matched
Logger::log(sprintf('No one route matched. URL: (%s)', $url));

echo $controllers['User']->notFoundAction();
