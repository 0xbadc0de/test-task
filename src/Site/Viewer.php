<?php namespace Site;

/**
 * Class Viewer
 * @package App
 */
class Viewer
{

    const TEMPLATE_DIR = __DIR__ . '/../../views/';

    /**
     * CI 2/3 Style render
     * See no reason to make instance of Viewer because of functionality lack
     * We can make it instance if we have (for example) view data sharing and e.g.
     * Like those we have in Laravel (View::share)
     *
     * @param string $template
     * @param array $data
     * @param integer $code
     * @return false|string
     */
    public static function render($template, $data = [], $code = 200)
    {
        http_response_code($code);

        extract($data);

        ob_start();

        $path = self::TEMPLATE_DIR . $template . '.php';

        if (is_file($path)) {
            include($path);
        }

        $content = ob_get_clean();

        return $content;
    }


}