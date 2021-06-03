<?php

    require_once(__DIR__ . '/initialization.php');

    session_start();

    use App\classes\Errors;
    use App\classes\View;
    use App\classes\Config;
    use App\classes\models\User;
    use App\classes\models\Article;

    $user = User::getCurrent(Config::getInstance()->PATH_TO_SESSIONS) ?? new User();

    if (empty($user->login)) {
        exit('No homo!');
    }

    $article = new Article();
    $errors = new Errors();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fields = extractFields(array_keys($_POST),$_POST);
        $article->setTitle($fields['title'])->setText($fields['text'])->setCategory($fields['category'])->setAuthor($user->login)->setAuthorId($user->id);
        $errors = $article->save();

        if (!$errors()) {
            header('Location: /article.php?id=' . $article->id);
        }
    }

    $currentPage = new View();
    $content = $currentPage->assign('article', $article)->assign('errors', $errors)->render('add');
    $currentPage->assign('title', 'Добавить статью')->assign('content',$content)->assign('name', $user->login)->display('layout');
