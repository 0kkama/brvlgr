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
        //        $fields['author'] = $user;
        $article->setTitle($fields['title'])->setText($fields['text'])->setCategory($fields['category']);

        if ($article->save() !== null) {
            header('Location: /article.php?id=' . $article->getId());
        }
    }

    $title = 'Редактировать статью';
    $currentPage = new View();
    $content = $currentPage->assign('article', $article)->render('add');
//    $content = $currentPage->render('add');
    $currentPage->assign('title', $title)->assign('content',$content)->assign('name', $user->getLogin())->display('layout');

    /*
     * TODO НУЖНО: 1. проверка того, переданы ли все необходимые поля при создании и редактировании статьи
        2. переделать класс Article для возможности полиморфного редактирования
        - если заходим на страницу без параметров, то формы пустые
            создаётся новый объект с параметрами, проверка заполнености всех полей, вызов метода insert
            переадресация по ИД
        - если заходим на страницу с параметром ИД, то проверка наличия этой статьи
            если статья есть, то данные распределяются по формам
            вызов метода update - проверка, были ли данные изменены и отправка только тех данных,
            которые были измененны в БД. переадресация по ИД
        ПЛАН:
        1) добавление статьи
            + корректное добавление статьи со всеми параметрами
            + корректная работа при заполненности не всех форм
            + перенаправление на статью при удачном добавлении
        2) редактирование статьи
            + получение статьи и корректное отображение данных в формах
            + проверка на заполненность всех форм
                ? необходимо допилить в дальнейшем с использованием исключений
            + перенаправление на статью при удачном редактировании
        3) попытка объединения этих действий в одном контроллере/шаблоне
            -
            -
            -

   */
