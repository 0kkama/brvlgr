<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\models\User;
    use App\classes\View;
    use JetBrains\PhpStorm\Pure;

    class Relocator extends Controller
    {
        protected string $message, $header;
        protected static array $signals =
            [
                400 => ['400 Bad request', 'Некорректный запрос'],
                403 => ['403 Forbidden', 'Данное действие требует авторизации'],
                404 => ['404 Not Found', 'Страница не найдена'],
                410 => ['410 Gone', 'Запрашиваемый ресурс удалён с сервера'],
                418 => ['418 I\'m a teapot', 'Что-то странное произошло. Сообщите администратору'],
                423 => ['423 Locked', 'Ресурс не может быть получен'],
                429 => ['429 Too Many Requests', 'С вашего IP поступает слишком много запросов. Попробуйте повторить запрос позже'],
            ];

        public function __construct()
        {
            parent::__construct();

            $number =  is_int(($_SESSION['number'])) ? (int) ($_SESSION['number']) : 418;
            $this->message = self::$signals[$number][1];
            $this->title = self::$signals[$number][0];
            $this->header = 'ERROR ' . $this->title . '!';
        }

        public static function deadend(int $number = 418) : void
        {
            $_SESSION['number'] = $number;
            header(Config::getInstance()->PROTOCOL . ' ' . self::$signals[$number][0]);
            header('Location: ' . '\?cntrl=relocator');
        }

        public function __invoke()
        {
            unset($_SESSION['number']);

            $this->content = $this->page->assignArray(
                [
                    'header' => $this->header,
                    'title' => $this->title,
                    'message' => $this->message,
                ])->render('error');
            parent::__invoke();
        }
    }

