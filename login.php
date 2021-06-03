<?php
    require_once(__DIR__ . '/initialization.php');
    session_start();

    use App\classes\View;
    use App\classes\Config;
    use App\classes\models\User;

    $user = User::getCurrent(Config::getInstance()->PATH_TO_SESSIONS) ?? new User();

    if(!empty($user->login)) {
        header('Location: index.php'); exit();
    }

    $loginErr = null;

    if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
        $user->setLogin(val($_POST['login']));
        if ($user->checkPassword(val($_POST['password']))) {
            $token = makeToken();
            $fileName = __DIR__ . '/resources/sessions.json';
            $data = json_encode(['user' => $user->login, 'token' => $token, 'date' => time()]);
//              помещаем данные в файл сессий, в куки и в массив сессий
            setcookie('token', $token, time() + 86400, Config::getInstance()->BASE_URL);
            $_SESSION['user'] = $user->login;
            $_SESSION['token'] = $token;

            file_put_contents($fileName, $data . "\n", FILE_APPEND);
            header('Location: index.php');
        }
        else {
            $loginErr = true;
        }
    }

    $title = 'Войти на сайт';

    $loginPage = new View();
    $content = $loginPage->assign('loginErr', $loginErr)->render('login');
    $loginPage->assign('title', $title)->assign('content',$content)->display('layout');
