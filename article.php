<?php
    require_once('initialization.php');
    session_start();

    use App\classes\View;
    use App\classes\Config;
    use App\classes\publication\User;
    use App\classes\publication\Article;

     $user = User::getCurrent(Config::getInstance()->PATH_TO_SESSIONS) ?? new User();

    $artId = $_GET['id'] ?? null;
    if ($artId === null) {
        exit('Некорректный ID');
    }

    $articlePage = new View();
    $article = Article::findById($artId);

    if (is_null($article)) {
        exit('NET TAKOY STATYI');
    }

    $content = $articlePage->assign('title', $article->getTitle())->assign('article',$article)->render('article');
    $articlePage->assign('content', $content)->assign('name', $user->getLogin())->display('layout');
