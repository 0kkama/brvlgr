<?php
        //    require_once(__DIR__ . '/../initialization.php');

    //<editor-fold desc="initialization">
    declare(strict_types=1);

    use App\classes\Config;

    setlocale(LC_ALL, "ru_RU.UTF-8");
    date_default_timezone_set('Europe/Moscow');
    error_reporting(E_ALL);

    // set autoload
    spl_autoload_register(static function($className) {
        $include = __DIR__ . '/../' . str_replace('\\', '/', $className) . '.php';
        require_once $include;
    });

    // set config instance
    Config::getInstance()->setInstance(include (__DIR__ . '/../config/config.php'));

    // include DEBUGGER
    include_once (__DIR__ . '/../utility/debug.util.php');
    set_error_handler('err_catcher');

    // include MODEL
    include_once (__DIR__ . '/../models/libra.php');
    //</editor-fold>

    //    /var/lib/php/sessions
    session_start();

    //<editor-fold desc="USE">
//    use App\classes\Config;
//    use App\classes\models\News;
//    use App\classes\models\User;
//    use App\classes\models\Article;
//    use App\classes\controllers;
//    use App\classes\controllers\Index;
//    use App\classes\View;
    //</editor-fold>

    $cntrl = $_GET['cntrl'] ?? 'Index';
    $cntrl = ucfirst(val($cntrl));
    $id = $_GET['id'] ?? null;

    $class = "App\classes\controllers\\$cntrl";
    $cntrl = new $class;
    $cntrl();
    echo ($_SERVER['REQUEST_METHOD']);


    //<editor-fold desc="TODO">
//    TODO решить проблему с повторной отправкой данных при F5 на Login и Gallery
//    TODO сохранится после этго в git !!!!!
//    TODO допилить взаимодействие других классов с MyError
//    TODO доделать ДЗ по контроллерам
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

