<?php
    include_once(__DIR__ . '/initialization.php');
    session_start();

    use App\classes\publication\Article;
    use App\classes\View;
    use App\classes\Config;
    use App\classes\publication\User;

    $user = User::getCurrent(Config::getInstance()->PATH_TO_SESSIONS) ?? new User();
    $title = 'Новости';

    $news =  Article::getAll();
    $currentPage = new View();

    $content = $currentPage->assign('news', $news)->render('news');
    $currentPage->assign('title', $title)->assign('content',$content)->assign('name', $user->getLogin())->display('layout');

