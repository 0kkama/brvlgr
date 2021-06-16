<?php
//
    require_once(__DIR__ . '/../initialization.php');

    use App\classes\Config;
    use App\classes\models\News;
    use App\classes\models\User;
    use App\classes\models\Article;
    use App\classes\controllers;
    use App\classes\controllers\Index;
    use App\classes\View;

    session_start();

//    $cntrl = $_GET['cntrl'] ?? 'Index';
//    $cntrl = ucfirst(val($cntrl));
//    $id = $_GET['id'] ?? null;
//
//    $class = "App\classes\controllers\\$cntrl";
//    $cntrl = new $class;
//    $cntrl();

    $id = $_GET['id'];

    $value = Article::findById($id);

    var_dump($value);

    if($value->exist()) {
        echo '!!!!!!!!!';
    }









