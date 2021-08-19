<?php


    namespace App\classes\controllers\user;

    use App\classes\abstract\ControllerAbstraction;
    use App\classes\Config;
    use JetBrains\PhpStorm\NoReturn;

    class Logout extends ControllerAbstraction
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
