<?php
    require_once(__DIR__ . '/initialization.php');
    //    /var/lib/php/sessions
    session_start();

    use App\classes\View;
    use App\classes\Config;
    use App\classes\models\User;
    use App\classes\models\Article;

    //заглушка: если пользователь не найден, то создаём новый пустой объект User для избежания ошибки при вызове getLogin из null
    $user = User::getCurrent(Config::getInstance()->PATH_TO_SESSIONS) ?? new User();
    $title = 'Главная';
    $news =  Article::getLast(5);
    $currentPage = new View();

    $content = $currentPage->assign('news', $news)->render('news');
    $currentPage->assign('title', $title)->assign('content', $content)->assign('name', $user->getLogin())->display('layout');

    var_dump($_SERVER['REQUEST_URI']);
