<?php
    require_once('initialization.php');
    session_start();
    use App\classes\View;
    use App\classes\publication\Article;

     $user = User::getCurrentUser($config->PATH_TO_SESSIONS) ?? new User();

    if (empty($user)) {
       exit('No homo!');
    }

//    $article = new Article();
//    получение данных уже существующей статьи
    if (!empty($_GET['id'])) {
        $id = val($_GET['id']);
        $article = Article::findById($id);

        if (is_null($article)) {
            header($config->PROTOCOL . ' 404 Not Found');
            exit('This article does\'t exist');
        }
    }

    //  отправка данных
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fields = extractFields(array_keys($_POST),$_POST);
        $fields['author'] = $user;
        $article = new Article();
        $article->setTitle($fields['title'])->setText($fields['text'])->setCategory($fields['category'])->setAuthor($fields['author']);
        if ($article->save() !== null) {
            header('Location: /article.php?id=' . $article->getId());
        }
    }

    $title = 'Формирование статьи';
    $currentPage = new View();
    $content = $currentPage->assign('article', $article)->render('add');
    $currentPage->assign('title', $title)->assign('content',$content)->assign('name', $user->getLogin()->display('layout');

//    TODO продумать возможность определения в POST происходит ли редактирование уже существующей или создание новой статьи
//      так же подумать над возможность реализации в update() обновления только подвергшихся изменению статей

