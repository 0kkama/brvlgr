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

    if (!empty($_GET['id'])) {
        $id = val($_GET['id']);
        $article = Article::findById($id);

        if (is_null($article)) {
            header(Config::getInstance()->PROTOCOL . ' 404 Not Found');
            exit('This article doesn\'t exist');
        } else {
            $article->delete();
            header('Location: ' . Config::getInstance()->BASE_URL);
        }
    }





/*    TODO
       1) удаление статьи
        2) проверка на существование статьи
        3) перенаправление на главную страницу
    */
