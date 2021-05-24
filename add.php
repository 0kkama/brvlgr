<?php
    require_once('initialization.php');

    session_start();

    use App\classes\View;
    use App\classes\Config;
    use App\classes\publication\User;
    use App\classes\publication\Article;

    $user = User::getCurrent(Config::getInstance()->PATH_TO_SESSIONS) ?? new User();

    if (empty($user->getLogin())) {
        exit('No homo!');
    }

    $article = new Article();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fields = extractFields(array_keys($_POST),$_POST);
        $fields['author'] = $user->getLogin();
//        $article = new Article();
        $article->setTitle($fields['title'])->setText($fields['text'])->setCategory($fields['category'])->setAuthor($fields['author']);

        if ($article->save() !== null) {
            header('Location: /article.php?id=' . $article->getId());
        }
    }

    $title = 'Добавить статью';
    $currentPage = new View();
    $content = $currentPage->assign('article', $article)->render('add');
    $currentPage->assign('title', $title)->assign('content',$content)->assign('name', $user->getLogin())->display('layout');
