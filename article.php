<?php
    require_once(__DIR__ . '/initialization.php');
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

    $article = Article::findById(val($artId));

    if (is_null($article)) {
        exit('NET TAKOY STATYI');
    }

    $currentPage = new View();
    $content = $currentPage->assign('title', $article->title)->assign('article', $article)->assign('author', $article->author())->render('article');
    $currentPage->assign('content', $content)->assign('name', $user->login)->display('layout');
