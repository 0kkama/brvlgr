<?php
    require_once(__DIR__ . '/initialization.php');
    session_start();

    use App\classes\Uploader;
    use App\classes\View;
    use App\classes\Config;
    use App\classes\models\User;

     $user = User::getCurrent(Config::getInstance()->PATH_TO_SESSIONS) ?? new User();

// TODO подумать, не перемудрил ли я здесь с условием
    if ( !empty($user) && ($_SERVER['REQUEST_METHOD'] = 'POST') && (isset($_FILES['newimage'])) ) {
        $newImage = new Uploader($_FILES['newimage'])  ;
        $newImage->upload($user);
        $errMsg = $newImage->showErrorStatus();
    }
        $errMsg = $errMsg ?? null;

    $title = 'Галерея';
    $list = glob("resources/img/cats/*.{jpg,jpeg}", GLOB_BRACE);
//    var_dump($list);

    $galleryPage = new View();
    $content = $galleryPage->assign('list', $list)->assign('errMsg', $errMsg)->assign('name', $user->getLogin())->render('gallery');
    $galleryPage->assign('title', $title)->assign('content', $content)->display('layout');

    /*
        TODO 1. м.б. добавить рандомайзер имени для файла
        TODO 2. добавить проверку совпадения нового имени и уже существущих
    */
