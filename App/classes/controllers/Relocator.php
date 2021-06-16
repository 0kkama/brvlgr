<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\models\User;
    use App\classes\View;
    use JetBrains\PhpStorm\Pure;

    class Relocator
    {
        protected User $user;
        protected View $page;
        protected string $message, $header, $content;
        protected static array $signals =
            [
                '400' => '400 Bad request',
                '403' => '403 Forbidden',
                '404' => '404 Not Found',
                '418' => '418 I\'m a teapot',
                '423' => '423 Locked',
            ];

        #[Pure] public function __construct()
        {
            $number = $_SESSION['number'] ?? '400';
            $this->message = $_SESSION['message'] ?? 'YOU SHALL NOT PASS!';
            $this->page = new View();
            $this->user = User::getCurrent(Config::getInstance()->SESSIONS);
            $this->title = self::$signals[$number];
            $this->header = 'ERROR ' . $this->title . '!';
        }

        public static function deadEnd(string $number, string $message) : void
        {
            $_SESSION['number'] = $number ?? '400';
            $_SESSION['message'] = $message ?? 'YOU SHALL NOT PASS!';
            header(Config::getInstance()->PROTOCOL . ' ' . self::$signals[$number]);
            header('Location: ' . '\?cntrl=relocator');
        }

        public function __invoke()
        {
            unset($_SESSION['number'], $_SESSION['message']);

            $this->content = $this->page->assignArray(
                [
                    'header' => $this->header,
                    'title' => $this->title,
                    'message' => $this->message,
                ])->render('error');

            $this->page->assign('title', $this->title)->assign('content', $this->content)->assign('user', $this->user)->display('layout');

        }


        //

    }

