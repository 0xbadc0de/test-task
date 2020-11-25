<?php namespace Site;

/**
 * Class Controller
 * @package App
 */
abstract class Controller
{

    abstract function getAction();

    abstract function postAction();

    abstract function deleteAction();

    /**
     * Not found php
     *
     * @return false|string
     */
    public function notFoundAction()
    {
        return Viewer::render('errors/404', [], 404);
    }

    /**
     * Return JSON response
     *
     * @param array $data
     * @param int $code
     */
    public function returnJson($data, $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json');
        return json_encode($data);
    }

}