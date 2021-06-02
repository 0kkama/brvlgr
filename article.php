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
        header(Config::getInstance()->PROTOCOL . ' 400 Bad Request');
        die('Некорректный ID');
    }

    $article = Article::findById(val($artId));

    if (is_null($article)) {
        header(Config::getInstance()->PROTOCOL . ' 404 Not Found');
        die('NET TAKOY STATYI');
    }

    $currentPage = new View();
    $content = $currentPage->assign('title', $article->title)->assign('article', $article)->assign('author', $article->author())->render('article');
    $currentPage->assign('content', $content)->assign('name', $user->login)->display('layout');
