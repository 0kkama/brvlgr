<?php
    require_once(__DIR__ . '/initialization.php');
    session_start();

    use App\classes\Config;
    use App\classes\publication\User;
    use App\classes\View;

    $user = User::getCurrent(Config::getInstance()->PATH_TO_SESSIONS) ?? new User();

    if (!isset($_GET['id'])) {
        echo 'Error!'; exit();
    }

    $title = 'Изборажение';
    $imgID = $_GET['id'];
    $list = glob('resources/img/cats/*');

    $imagePage = new View();
    $content = $imagePage->assign('list', $list)->assign('imageID', $imgID)->render('image');
    $imagePage->assign('title', $title)->assign('content', $content)->assign('name', $user->getLogin())->display('layout');
