<?php
    // base SETTINGS
    declare(strict_types=1);
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
    const PATH_TO_CONFIG = __DIR__ . '/config.php';
    \App\classes\Config::getInstance()->setInstance(include (PATH_TO_CONFIG));
//    $config = \App\classes\Config::getInstance()->setInstance(include (PATH_TO_CONFIG);
//    $config->setInstance(include (PATH_TO_CONFIG));
//    unset($config);
    // TODO проверить корректность синглтона
    // constants
    define('PROTOCOL', $_SERVER['SERVER_PROTOCOL']);
    const BASE_URL = '/';
    const AUTH_LOG_PATH = __DIR__ . '/logs/auth/';
    const PATH_FOR_IMG = __DIR__ . '/resources/img/cats/';
    const PATH_TO_ARTICLES = __DIR__ . '/resources/articles/';
    const PATH_TO_SESSIONS = __DIR__ . '/resources/sessions.json';
    const PATH_TO_RECORDS = __DIR__ . '/resources/txt/guests.json';
    const PATH_TO_TRACE = __DIR__ . '/logs/trace/';
    const PATH_TO_TEMPLATES = __DIR__ . '/views/';

    // include CORE UTILITES
    include_once (__DIR__ . '/utility/system.util.php');
    include_once (__DIR__ . '/utility/validat.util.php');

    // include MODEL
    include_once (__DIR__ . '/models/libra.php');
    include_once (__DIR__ . '/models/authorization.mod.php');

    $users = include (__DIR__ . '/resources/users.data.php');
    define('USERS_LIST', $users);
    unset($users);

    //     CONST for DataBase
//    const DB_HOST = 'localhost';
//    const DB_NAME = 'profit';
//    const DB_USER = 'admin';
//    const DB_PASS = '14133788';
//    const DB_CHAR = 'utf8';

    // include classes
//    include_once (__DIR__ . '/classes/DB.php');
//    include_once (__DIR__ . '/classes/TextFile.php');
//    include_once (__DIR__ . '/classes/GuestBook.php');
//    include_once (__DIR__ . '/classes/Uploader.php');
//    include_once (__DIR__ . '/classes/View.php');
//    include_once (__DIR__ . '/classes/Article.php');
//    include_once (__DIR__ . '/classes/Article.php');
//    include_once (__DIR__ . '/classes/News.php');
//    include_once (__DIR__ . '/classes/News.php');
