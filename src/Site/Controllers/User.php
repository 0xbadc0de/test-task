<?php namespace Site\Controllers;

use Site\Controller;
use Site\Logger;
use Site\Viewer;

/**
 * Class User
 * @package Site\Controllers
 */
class User extends Controller
{

    /**
     * Returns user list
     *
     * @return string
     */
    public function getAction()
    {
        $data = [
            'list' => $this->readUsers()
        ];

        return Viewer::render('user/list', $data);
    }

    /**
     * Creates a new user
     *
     * @return string
     */
    public function postAction()
    {
        // Validate
        if (!isset($_POST['name']) || !isset($_POST['email'])) {
            return $this->returnJson(['message' => 'Invalid data specified'], 412);
        }

        if (strlen($_POST['name']) < 1 || strlen($_POST['email']) < 1) {
            return $this->returnJson(['message' => 'Invalid data specified'], 412);
        }

        // NOTE: Here we can replace this simple validation by validating MX records of domain
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->returnJson(['message' => 'Invalid email specified'], 412);
        }

        // We don't want to write whole $_POST, because there can be other parameters
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email']
        ];

        // Get current users list
        $userList = $this->readUsers();

        // Append user to the list
        array_push($userList, $data);

        // Finally, write file
        $this->writeUsers($userList);

        return $this->returnJson(['status' => 'ok']);
    }

    /**
     * Deletes an user by position
     */
    public function deleteAction()
    {
        if (!isset($_GET['position'])) {
            http_response_code(412);
            return;
        }

        $position = (int)$_GET['position'];

        $users = $this->readUsers();

        // Validate position exists
        $entry = @$users[$position];

        if (!$entry) {
            http_response_code(412);
            return;
        }

        unset($users[$position]);

        $data = array_values($users);

        $this->writeUsers($data);

        http_response_code(200);
    }

    /**
     * Read user list
     *
     * @return array
     */
    private function readUsers()
    {
        $filePath = storage_path('users.json');

        Logger::log(sprintf('[User] appendUser Reading users.json from file: %s', $filePath));

        // Return empty array if no file exists
        if (!is_file($filePath))
            return [];

        // Optimized for loading big files
        $fd = fopen($filePath, 'r');
        $chinkSize = 1024 * 1024;

        $content = '';

        if ($fd) {
            while (!feof($fd)) {
                $content .= fread($fd, $chinkSize);
            }
            fclose($fd);
        }

        $users = json_decode($content, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            Logger::log(sprintf('[User] readUsers JSON Parsing error! Error: %s (%s)', json_last_error(), json_last_error_msg()));
            return [];
        }

        return $users;
    }

    /**
     * Write users to file
     *
     * @param array $users
     */
    private function writeUsers($users)
    {
        $filePath = storage_path('users.json');

        Logger::log(sprintf('[User] writeUsers Writing users.json to file: %s', $filePath));

        $fd = fopen($filePath, 'w');

        fwrite($fd, json_encode($users));

        fclose($fd);
    }

}