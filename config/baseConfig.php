<?php

class BaseConfig {

    // base url generate for dynamic way
    public static $BASE_URL;
    public static $BASE_PATH;

    public static function init()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                    ? "https://"
                    : "http://";


        $host = $_SERVER['HTTP_HOST'];

        // Detect project folder name automatically
        $projectFolder = basename(dirname(__DIR__));  

        // Build BASE_URL dynamically
        self::$BASE_URL = $protocol . $host . "/" . $projectFolder . "/";


        self::$BASE_PATH = dirname(__DIR__) . "/";
    }

}

BaseConfig::init();
?>