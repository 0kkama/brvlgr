<?php
    //<editor-fold desc="INITIALIZATION">
    // base settings
    declare(strict_types=1);
    setlocale(LC_ALL, "ru_RU.UTF-8");
    date_default_timezone_set('Europe/Moscow');
    error_reporting(E_ALL);

//    include_once (__DIR__ . '/../utility/debug.util.php');
//    set_error_handler('err_catcher', E_ALL);

    use App\classes\Config;
    use App\classes\utility\LittleLogger;
    use App\classes\controllers\Error;
    use App\classes\exceptions\FullException;
    use App\classes\utility\Router;
    use SebastianBergmann\Timer\ResourceUsageFormatter;
    use Psr\Log\LoggerInterface as PsrLog;

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

    set_error_handler('App\classes\utility\LittleLogger::errorCatcher', E_ALL);
    // set config instance
    Config::getInstance()->setInstance(include (__DIR__ . '/../config/config.php'));

    // include library
    include_once (__DIR__ . '/../models/libra.php');
    //</editor-fold>

    //    /var/lib/php/sessions
    session_start();

    $uri = $_SERVER['REQUEST_URI'];
    $router = new Router($uri);
    $params = $router();

    $cntrl = ucfirst($params['controller']);
    $className = "App\classes\controllers\\$cntrl";

    if (!class_exists($className)) {
        trigger_error("Контроллер несуществующего класса $className в " . __FILE__ . __LINE__ );
        Error::deadEnd(400);
    }

    try {
        $controller = new $className($params);
        $controller();
    }
    catch (FullException $ex) {
        LittleLogger::create($ex)->write();
        Error::deadend($ex->getCode(), $ex->getAlert());
    }
    catch (Exception $ex) {
        LittleLogger::create($ex)->write();
        Error::deadend($ex->getCode());
    }

<<<<<<< HEAD
    use Monolog\Logger as Monologger;
    use Monolog\Handler\ErrorLogHandler;
    use App\classes\testexamples\LoggerTest;

    $logger = new Monologger('test_logger');
    $logger->pushHandler(new ErrorLogHandler());

    //    $logger->info('test message and {user}');
    $logger->info('This is a log! ^_^ ');
    $logger->warning('This is a log warning! ^_^ ');
    $logger->error('This is a log error! ^_^ ');


    echo $_SERVER['SERVER_PROTOCOL'];
=======
    echo $df;

>>>>>>> f1a9fe4d2c009c5a18d4cea14c172c51e5872621
//    вывод данных о ресурсах
    echo (new ResourceUsageFormatter)->resourceUsageSinceStartOfRequest();

    //<editor-fold desc="TODO">
/* TODO
    - допилить MultiException
    - решить проблему с повторной отправкой данных при F5 на Login и Gallery
    - решить проблему с костылями и путями на Login и Gallery
    - доделать конфиг nginx для запрета доступа ко всем директориям, кроме public
          а так же файлам типо /css/style.css
    - https://stackoverflow.com/questions/40966017/nginx-deny-access-of-a-directory-and-files-inside-it
    - сделать доступ по домену (http)
    1. + Добавьте в свой проект класс исключений, возникающих при работе с базой данных.
            Придумайте - где их можно бросать? Как вариант - нет соединения с БД, ошибка в запросе.
    2. Ловите исключения из пункта 1 во фронт-контроллере, поймав же,
       выдавайте пользователю красивую страницу с сообщением об ошибке.
    3. Добавьте класс исключений, означающих "Ошибка 404 - не найдено". Бросайте такое исключение в ситуациях,
       когда вы не можете найти в базе запрашиваемую запись. Добавьте обработку исключений этого типа во фронт-контроллер.
    4. Добавьте в модель новостей (а лучше - в базовую модель) метод fill(array $data),
       который заполняет свойства модели данными из массива. Примените в этом методе паттерн "Мультиисключение".
        + 5*. Добавьте в свой проект класс-логгер. Его задача - записывать в текстовый лог информацию об
       ошибках - когда и где возникла ошибка, какая. Логируйте исключения из пунктов 1 и 3.
*/
        //</editor-fold>


