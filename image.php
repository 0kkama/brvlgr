<?php
    require_once('initialization.php');
    session_start();
     $user = User::getCurrentUser($config->PATH_TO_SESSIONS) ?? new User();

    if (!isset($_GET['id'])) {
        echo 'Error!'; exit();
    }

    $title = 'Изборажение';
    $imgID = $_GET['id'];
    $list = glob('resources/img/cats/*');

    $imagePage = new \classes\View();
    $content = $imagePage->assign('list', $list)->assign('imageID', $imgID)->render('image');
    $imagePage->assign('title', $title)->assign('content', $content)->assign('name', $user->getLogin()->display('layout');
