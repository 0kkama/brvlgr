<?php


    namespace App\classes\controllers\user;

    use App\classes\abstract\controllers\Controller;
    use App\classes\utility\Config;
    use JetBrains\PhpStorm\NoReturn;

    class Logout extends Controller
    {

        #[NoReturn] public function __invoke()
        {
            unset($_SESSION['token']);
            $url = Config::getInstance()->BASE_URL;
            setcookie('token', '', time() - 86400, $url);
            session_destroy();
            header('Location: ' . $url);
            exit();
        }
    }
