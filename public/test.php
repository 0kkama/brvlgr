<?php
    //<editor-fold desc="INITIALIZATION">
    declare(strict_types=1);

    use App\classes\Config;
    use App\classes\controllers\Error;
    use App\classes\exceptions\DbException;
    use App\classes\utility\Router;

    // base settings
    setlocale(LC_ALL, "ru_RU.UTF-8");
    date_default_timezone_set('Europe/Moscow');
    error_reporting(E_ALL);

    // set autoload
    spl_autoload_register(static function($className) {
        $include = __DIR__ . '/../' . str_replace('\\', '/', $className) . '.php';
        //        var_dump($include);
        if (file_exists($include)) {
            require_once $include;
        } else {
            var_dump($include);
            //            Relocator::deadend(400);
        }
    });

    // set config instance
    Config::getInstance()->setInstance(include (__DIR__ . '/../config/config.php'));

    // include DEBUGGER
    include_once (__DIR__ . '/../utility/debug.util.php');
    set_error_handler('err_catcher');

    // include library
    include_once (__DIR__ . '/../models/libra.php');

    //</editor-fold>

    //    /var/lib/php/sessions
    session_start();

//$ex = new Exception();

//
    try {

//    $ex = new \App\classes\exceptions\FullException();
        $exe = new Exception('Строка сообщения для логов', 500);
        throw $exe;
        //        $exe->setLog('Строка логов')->setParam('Строка парамтров')->throwIt();
//    DbException::create('Ошибка в базе данных', 500)->setLog('Message')->throwIt();

    } catch (Exception $ex) {
\App\classes\utility\Logger::create($ex)->write();

    var_dump($ex);
    }
//\App\classes\utility\Logger::create($ex)->write();





    //    $ex = new \App\classes\exceptions\DbException('Какая-то хуйня', 404);
//    $ex->setLog('Полетел запрос в пизду')->setParam('SELECT + START');
//
//    var_dump($ex);






