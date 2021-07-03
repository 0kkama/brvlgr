<?php


    namespace App\classes\controllers;


    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\models\User;
    use App\classes\utility\UsersErrors;
    use App\classes\View;
    use JetBrains\PhpStorm\Pure;

    class Error extends Controller
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
                434 => ['434 Requested host unavailable', 'Запрашиваемый адрес недоступен'],
                456 => ['456 Unrecoverable Error', 'Обработка запроса вызвала сбой в работе сервера'],
                500 => ['500 Internal Server Error', 'Ошибка на стороне сервера'],
                501 => ['501 Not Implemented', 'Сервер не поддерживает данный функционал'],
                502 => ['502 Bad Gateway', 'Неверный ответ вышестоящего сервера или прокси-сервера'],
                503 => ['Service Unavailable', 'Служба временно не доступна. Попробуйте повторить запрос позднее'],
                504 => ['Gateway Time-Out', 'Шлюз или прокси-сервер временно заблокирован. Попробуйте повторить запрос позже'],
            ];

        public function __construct($params)
        {
            $this->page = new View();
            $this->errors = new UsersErrors();
            $this->params = $params;
            $this->user = new User();
//            $this->user = User::getCurrent(Config::getInstance()->SESSIONS);
//            parent::__construct($params);


            if (empty($params['id']) || !is_numeric($params['id']) || !array_key_exists($params['id'], self::$signals)) {
                $number = 418;
            }
            else {
                $number = (int) ($params['id']);
            }

            $this->title = self::$signals[$number][0];
            $this->message = $_SESSION['errorMessage'] ?? self::$signals[$number][1] ;
            unset($_SESSION['errorMessage']);
            $this->header = 'ERROR: ' . $this->title . '!';
        }

        public static function deadend(int|string $number = 418, $message = '') : void
        {
            if ($message !== '') {
                $_SESSION['errorMessage'] = $message;
            }

            if (!is_numeric($number)) {
                $number = 418;
            }

            header(Config::getInstance()->PROTOCOL . ' ' . self::$signals[$number][0]);
            header('Location: ' . '/error/' . $number);
            die();
        }

        public function __invoke()
        {
            $this->content = $this->page->assignArray(
                [
                    'header' => $this->header,
                    'title' => $this->title,
                    'message' => $this->message,
                ])->render('error');
//            $this->page->assign('title', $this->title)->assign('content', $this->content)->assign('user', $this->user)->display('layout');
            parent::__invoke();
        }
    }

