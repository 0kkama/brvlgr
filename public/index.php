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
    // include DEBUGGER
    include_once (__DIR__ . '/../utility/debug.util.php');
    set_error_handler('err_catcher');

    // set autoload
    spl_autoload_register(static function($className) {
        $include = __DIR__ . '/../' . str_replace('\\', '/', $className) . '.php';
        if (file_exists($include)) {
            require_once $include;
        } else {
            trigger_error("Файл с классом $include не существует");
            Relocator::deadend(400); exit();
        }
    });

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

    var_dump($params);

    $cntrl = ucfirst($params['controller']);
    $className = "App\classes\controllers\\$cntrl";

    if (!class_exists($className)) {
        Relocator::deadEnd(400);
    }

    $controller = new $className($params);
    $controller();

    echo ($_SERVER['REQUEST_METHOD']);

    //<editor-fold desc="TODO">
/* TODO
    - решить проблему с повторной отправкой данных при F5 на Login и Gallery
    - доделать ДЗ по контроллерам
    - доделать конфиг nginx для запрета доступа ко всем директориям, кроме public
          а так же файлам типо /css/style.css
    - https://stackoverflow.com/questions/40966017/nginx-deny-access-of-a-directory-and-files-inside-it
    - сделать доступ по домену
    -
*/
        //</editor-fold>

    //<editor-fold desc="OLD CODE">
    //    $cntrlName = $_SERVER['REQUEST_URI'] ?? 'Index';
//    $class = 'App\classes\controllers\Index';
//
//    $class = Index::class;
//    $controller = new $class();
//
////    заглушка: если пользователь не найден, то создаём новый пустой объект User для избежания ошибки при вызове getLogin из null
//    $user = User::getCurrent(Config::getInstance()->SESSIONS) ?? new User();
//    $news =  Article::getLast(5);
//    $currentPage = new View();
//
//    $content = $currentPage->assign('news', $news)->render('news');
//    $currentPage->assign('title', $title)->assign('content', $content)->assign('name', $user->getLogin())->display('layout');
//
//    var_dump($_SERVER['REQUEST_URI']);
//    var_dump($cntrlName);
    //</editor-fold>

