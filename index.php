<?php
    require_once('initialization.php');
    //    /var/lib/php/sessions
    session_start();

    use App\classes\View;
    use App\classes\Config;
    use App\classes\publication\User;
    use App\classes\publication\Article;

        //заглушка: если пользователь не найден, то создаём новый пустой объект User для избежания ошибки при вызове getLogin из null
        //    $user = User::getCurrent($config->PATH_TO_SESSIONS) ?? new User();
    $user = User::getCurrent(Config::getInstance()->PATH_TO_SESSIONS) ?? new User();
    $title = 'Главная';
    $news =  Article::getLast(5);
    $currentPage = new View();

    $content = $currentPage->assign('news', $news)->render('news');
    $currentPage->assign('title', $title)->assign('content',$content)->assign('name', $user->getLogin())->display('layout');

    //var_dump($user);
