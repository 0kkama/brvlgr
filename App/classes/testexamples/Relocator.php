<?php


    namespace App\classes\controllers\test;


    use App\classes\abstract\controllers\ControllerActing;
    use App\classes\utility\Config;
    use App\classes\models\User;
    use App\classes\utility\View;
    use JetBrains\PhpStorm\Pure;

    class Relocator extends ControllerActing
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

        public function __construct($params)
        {
            parent::__construct($params);

            if (empty($params['id']) || !is_numeric($params['id']) || !array_key_exists($params['id'], self::$signals)) {
                $number = 418;
            } else {
                $number = (int) ($params['id']);
            }

            $this->title = self::$signals[$number][0];
            $this->message = self::$signals[$number][1];
            $this->header = 'ERROR: ' . $this->title . '!';
        }

        public static function deadend(int $number = 418) : void
        {

            header(Config::getInstance()->PROTOCOL . ' ' . self::$signals[$number][0]);
            header('Location: ' . '/error/' . $number);
        }

        public function __invoke()
        {
            $this->content = $this->page->assignArray(
                [
                    'header' => $this->header,
                    'title' => $this->title,
                    'message' => $this->message,
                ])->render('error');
            parent::__invoke();
        }
    }

