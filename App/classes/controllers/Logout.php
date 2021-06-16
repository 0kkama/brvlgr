<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\Config;
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
