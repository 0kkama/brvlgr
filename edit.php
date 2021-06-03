<?php
    require_once(__DIR__ . '/initialization.php');

    session_start();

    use App\classes\View;
    use App\classes\Config;
    use App\classes\models\User;
    use App\classes\models\Article;

    $user = User::getCurrent(Config::getInstance()->PATH_TO_SESSIONS) ?? new User();

    if (empty($user->getLogin())) {
       exit('No homo!');
    }

    $article = new Article();

    //    получение данных уже существующей статьи
    if (!empty($_GET['id'])) {
        $id = val($_GET['id']);
        $article = Article::findById($id);

        if (is_null($article)) {
            header(Config::getInstance()->PROTOCOL . ' 404 Not Found');
            exit('This article doesn\'t exist');
        }
     }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fields = extractFields(array_keys($_POST),$_POST);
        $article->setTitle($fields['title'])->setText($fields['text'])->setCategory($fields['category']);

        if ($article->save() !== null) {
            header('Location: /article.php?id=' . $article->id);
        }
    }

    $title = 'Редактировать статью';
    $currentPage = new View();
    $content = $currentPage->assign('article', $article)->render('add');
//    $content = $currentPage->render('add');
    $currentPage->assign('title', $title)->assign('content',$content)->assign('name', $user->getLogin())->display('layout');
