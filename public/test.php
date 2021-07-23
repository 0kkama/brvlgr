<?php
    //<editor-fold desc="INITIALIZATION">
    // base settings
    declare(strict_types=1);
    setlocale(LC_ALL, "ru_RU.UTF-8");
    date_default_timezone_set('Europe/Moscow');
    error_reporting(E_ALL);
    // include DEBUGGER
    include_once (__DIR__ . '/../helpers/debug.util.php');
    set_error_handler('err_catcher', E_ALL);

    use App\classes\Config;
    use App\classes\controllers\Error;
    use App\classes\exceptions\FullException;
    use App\classes\utility\LittleLogger;
    use App\classes\utility\Router;
    use FastRoute\RouteCollector;
    use Intervention\Image\ImageManagerStatic as Image;
    use Intervention\Image\ImageManager;

    // set composer autoload
    require __DIR__ . '/../vendor/autoload.php';

    // set autoload
    spl_autoload_register(static function($className) {
        $include = __DIR__ . '/../' . str_replace('\\', '/', $className) . '.php';
        if (is_readable($include)) {
            require_once $include;
        } else {
            trigger_error("Ошибка при попытке подключения класса $className. Файл $include не существует или повреждён");
            Error::deadend(400, 'Ошибка при подключении класса');
        }
    });

    // set config instance
    Config::getInstance()->setInstance(include (__DIR__ . '/../config/config.php'));

    // include help library
    require_once (__DIR__ . '/../helpers/libra.php');
    //</editor-fold>

    //    /var/lib/php/sessions
    session_start();

//    $ex1 = new JsonException('Test JSON exception');
//    $ex2 = new \App\classes\exceptions\FileException('Test exception #2');
//
//    $ll1 = LittleLogger::create($ex1)->write();
//    $ll2 = LittleLogger::create($ex2)->write();
//
//    echo get_class($ex1);
//    echo get_class($ex2);
//
//    var_dump($ll1, $ll2);



