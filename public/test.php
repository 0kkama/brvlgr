<?php
    //<editor-fold desc="INITIALIZATION">
    // base settings
    declare(strict_types=1);
    setlocale(LC_ALL, "ru_RU.UTF-8");
    date_default_timezone_set('Europe/Moscow');
    error_reporting(E_ALL);
    // include DEBUGGER
    include_once (__DIR__ . '/../utility/debug.util.php');
    set_error_handler('err_catcher', E_ALL);

    use App\classes\Config;
    use App\classes\controllers\Error;
    use App\classes\exceptions\FullException;
    use App\classes\utility\Logger;
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

    // include library
    include_once (__DIR__ . '/../models/libra.php');
    //</editor-fold>

    //    /var/lib/php/sessions
    session_start();

//
    // configure with favored image driver (gd by default)
//    $origPath = Config::getInstance()->IMG_PATH;
//    $prePath = Config::getInstance()->IMG_PRE;
//    $data = '/home/proletarian/Desktop/2/photo_1.jpg';
//    Image::configure(array('driver' => 'imagick'));
////    echo Image::canvas($data)->(800,600)
//
//    // create a new empty image resource
//    $img = Image::canvas(800, 600, '#ff0000');
//    $path = Config::getInstance()->IMG_PATH;
//    $list = scandir($origPath, SCANDIR_SORT_DESCENDING);
//    array_pop($list);
//    array_pop($list);
//
//    $arr = [];
//
//    $callback = static function (&$value, $key, $path) {
//        $value = $path . $value;
//        $img = Image::make($value)->resize(200,200)->encode('jpg');
//        $GLOBALS['arr'][] = $img;
//    };
//    array_walk($list, $callback, $path);


  
