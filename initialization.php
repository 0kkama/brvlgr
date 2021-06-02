<?php
    // base SETTINGS
//    declare(strict_types=1);

    use App\classes\Config;

    setlocale(LC_ALL, "ru_RU.UTF-8");
    date_default_timezone_set('Europe/Moscow');
    error_reporting(E_ALL);

    // include DEBUGGER
    include_once (__DIR__ . '/utility/debug.util.php');
    set_error_handler('err_catcher');
    // set autoload
    spl_autoload_register(static function($className) {
        require_once str_replace('\\', '/', $className) . '.php';
    });

    // set config instance
    Config::getInstance()->setInstance(include (__DIR__ . '/config.php'));

    // include CORE UTILITES
    include_once (__DIR__ . '/utility/system.util.php');
    include_once (__DIR__ . '/utility/validat.util.php');

    // include MODEL
    include_once (__DIR__ . '/models/libra.php');
    include_once (__DIR__ . '/models/authorization.mod.php');
