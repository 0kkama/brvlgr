<?php

    require_once(__DIR__ . '/initialization.php');

    use App\classes\Config;
    use App\classes\publication\News;
    use App\classes\publication\User;
    use App\classes\publication\Article;

    session_start();

    $x = Article::findById(4);
//
//    header(Config::getInstance()->PROTOCOL . ' 404 Not Found');
//    die();

//    $fileName = $config->PATH_TO_SESSIONS;
//    $userName = 'Dunduk';
//    $cookTkn = $_COOKIE['token'] ?? '';
//    $sessTkn = $_SESSION['token'] ?? '';
//
//    $user = User::getUserByLogin($userName);
//    $currUser = User::getCurrentUser($fileName, $cookTkn, $sessTkn);
////    var_dump($user);
//    var_dump($currUser);
////    $users = User::getAll();
//    var_dump($users);
//
//    var_dump(getUsersList());

    //    $user = getCurrentUser($config->PATH_TO_SESSIONS);
//     $user = User::getCurrentUser($config->PATH_TO_SESSIONS) ?? new User();
//    phpinfo();
//    --==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--==--





