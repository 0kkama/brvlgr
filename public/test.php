<?php
    //<editor-fold desc="INITIALIZATION">
    declare(strict_types=1);

    use App\classes\Config;
    use App\classes\controllers\Relocator;
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
            //            Relocator::deadend(400); exit();
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


//    $id = '';
//
//    $us = new \App\classes\models\User();
//
//    var_dump($us->getLogin());



//    $cntrl = $_GET['cntrl'] ?? 'Index';
//    $cntrl = ucfirst(val($cntrl));
//    $id = $_GET['id'] ?? null;
//
//    $class = "App\classes\controllers\\$cntrl";
//    $cntrl = new $class;
//    $cntrl();

//$uri = '/relocator/400';
//$uri = '/article/read/32/?dfdfdf=dsfs';
//
//    $pattern = '@(\?.*)?@';
//    $uri = preg_replace($pattern, '', $uri);
//
//$uri = trim($uri, '/');
//$value = explode('/', $uri);
//var_dump($value);
//var_dump($uri);

//$router = new \App\classes\utility\Router($uri);
//
//$result = $router();
//
//var_dump($result);


//    $id = $_GET['id'];
//
//    $value = Article::findById($id);
//
//    var_dump($value);
//
//    if($value->exist()) {
//        echo '!!!!!!!!!';
//    }









