<?php


    namespace App\classes\controllers;


    use App\classes\abstract\ControllerAbstraction;
    use App\classes\Config;
    use App\classes\exceptions\ExceptionWrapper;
    use App\classes\models\User;
    use App\classes\utility\EmailSender;
    use App\classes\utility\LoggerForExceptions;
    use App\classes\View;
    use JetBrains\PhpStorm\NoReturn;

    /**
     * Relocate user to respective blind plug by HTTP error code with short message about situation
    */
    class Error extends ControllerAbstraction
    {
        /**
         * @var string|mixed
         */
        protected string $header;
        /**
         * @var array|string[][]
         */
        protected static array $httpSignals =
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

        /**
         * Error constructor.
         * @param $params
         * @param View $templateEngine
         */
        public function __construct($params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            try {
                $this->user = User::getCurrent(Config::getInstance()->SESSIONS);
            } catch (ExceptionWrapper $ex) {
                (new LoggerForExceptions($ex, new EmailSender))();
                $this->user = new User();
            }
//          если ключ отсутствует или неверен, то присваивается дефолтное значение для сигнала
            if (empty($params['id']) || !is_numeric($params['id']) || !array_key_exists($params['id'], self::$httpSignals)) {
                $this->id = 418;
            } else {
                $this->id = (int) ($params['id']);
            }

            $this->title = self::$httpSignals[$this->id][0];
            $this->content = $_SESSION['errorMessage'] ?? self::$httpSignals[$this->id][1] ;
            unset($_SESSION['errorMessage']);
            $this->header = 'ERROR: ' . $this->title . '!';
        }

        /**
         * This method take two parameters and then call itself by 'header Location' with these parameters
         * @param int|string $code is HTTP-code for error blind plug
         * @param string $message optional message for user
         */
        #[NoReturn] public static function deadend(int|string $code = 418, string $message = '') : void
        {
            echo $code;

            if ($message !== '') {
                $_SESSION['errorMessage'] = $message;
            }

            if (!is_numeric($code)) {
                $code = 418;
            }

            header(Config::getInstance()->PROTOCOL . ' ' . self::$httpSignals[$code][0]);
            header('Location: ' . '/error/' . $code);
            die();
        }

        /**
         *
         */
        public function __invoke()
        {
            $this->content = $this->page->assignArray(
                [
                    'header' => $this->header,
                    'title' => $this->title,
                    'message' => $this->content,
                ])->render('error');
            parent::__invoke();
        }
    }

