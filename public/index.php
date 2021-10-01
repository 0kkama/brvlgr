<?php
    //<editor-fold desc="INITIALIZATION">
    declare(strict_types=1);
    setlocale(LC_ALL, "ru_RU.UTF-8");
    date_default_timezone_set('Europe/Moscow');
    error_reporting(E_ALL);

    use App\classes\utility\Config;
    use App\classes\utility\loggers\LoggerSelector;
    use App\classes\utility\View;
    use App\classes\controllers\Error;
    use App\classes\abstract\exceptions\CustomException;
    use App\classes\exceptions\ExceptionWrapper as MyExWrapper;
    use App\classes\utility\EmailSender;
    use App\classes\utility\Router;
    use SebastianBergmann\Timer\ResourceUsageFormatter;

    // set composer autoload
    require __DIR__ . '/../vendor/autoload.php';

    // set autoload
    spl_autoload_register(static function($className) {
        $include = __DIR__ . '/../' . str_replace('\\', '/', $className) . '.php';
        if (is_readable($include)) {
            require_once $include;
        } else {
            trigger_error("Ошибка при подключении класса $className. Файл $include не существует или повреждён");
        }
    });

    set_error_handler('App\classes\utility\loggers\LittleLogger::errorCatcher', E_ALL);
    // set config instance
    Config::getInstance()->setInstance(include (__DIR__ . '/../config/config.php'));

    // include library
    include_once (__DIR__ . '/../App/helpers/libra.php');
    //</editor-fold>

    //    /var/lib/php/sessions
    session_start();

    $uri = val($_SERVER['REQUEST_URI']);
    $params = (new Router($uri))();

    $cntrl = ucfirst($params['controller']);
    $className = "App\classes\controllers\\$cntrl";

    if (!class_exists($className)) {
        trigger_error("Контроллер несуществующего класса $className");
        Error::deadEnd(400);
    }

    try {
        // запуск контроллера с параметрами
        (new $className($params, new View))();
    }
    catch (CustomException|MyExWrapper $ex ) {
        LoggerSelector::exception($ex, new EmailSender);
        Error::deadend($ex->getHttpCode(), $ex->getAlert());
    } catch (Exception $ex) {
        LoggerSelector::exception($ex, new EmailSender);
        Error::deadend();
    }

//    var_dump($params);
//    вывод данных о ресурсах
//    echo (new ResourceUsageFormatter)->resourceUsageSinceStartOfRequest();

