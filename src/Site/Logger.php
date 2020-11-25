<?php namespace Site;

/**
 * Class Logger
 * @package App
 */
class Logger
{

    const LOG_DIR = __DIR__ . '/../../storage/logs/';

    /**
     * Simple logging function
     * Static - because there is no any other functionality
     * Can be "singletoned"
     *
     * @param string $string
     */
    public static function log($string)
    {
        $filename = 'app-' . date('d-m-Y') . '.log';

        $logPath = self::LOG_DIR . $filename;

        $fd = fopen($logPath, 'a');

        fwrite($fd, sprintf("[%s] %s \n", date('Y/m/d h:i:s'), $string));

        fclose($fd);
    }

}