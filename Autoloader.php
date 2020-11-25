<?php

/**
 * Class Autoloader
 */
class Autoloader
{

    /**
     * Register an Autoloader
     */
    public function register()
    {
        // Setup autoloading
        spl_autoload_register(function ($class) {
            $prefix = 'Site\\';

            $baseDir = __DIR__ . '/src/Site/';

            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                return;
            }

            $relativeClass = substr($class, $len);

            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

            if (file_exists($file)) {
                require $file;
            }
        });
    }

}