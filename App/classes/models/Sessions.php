<?php

    namespace App\classes\models;

    use App\classes\abstract\models\AbstractModel;
    use App\classes\utility\Config;
    use App\classes\utility\Time;

    class Sessions extends AbstractModel
    {
        //        TODO допилить этот класс
        //TODO решить проблему с неисчезающим при пустом обновлении всплывающим алертом о неверности логина или пароля
        // использовать сессию для передачи ошибки и переадресацию на страницу по новой
        protected const TABLE_NAME = 'sessions';
        protected string $user_id, $token;
        protected ?string $date_add = null;
        protected User $entering;

        public function createNewSession(User $entering, bool $checkbox = false) : void
        {
            $this->entering = $entering;
            $this->token = makeToken(32);
            $this->user_id = $this->entering->getId();
            $this->save();
            $_SESSION['user'] = $this->entering->getLogin();
            $_SESSION['id'] = $this->entering->getId();
            $_SESSION['token'] = $this->token;
            if (true === $checkbox) {
                $this->makeCookie();
            }
            header('Location: ' . Config::getInstance()->BASE_URL);
        }

        private function makeCookie() : void
        {
            setcookie('token', $this->token, time() + Time::daysFromNow(14), Config::getInstance()->BASE_URL);
        }

        /**
         * @throws \App\classes\exceptions\CustomException
         */
        public static function getCurrent() : User
        {
            $cookeToken = $_COOKIE['token'] ?? null; // TODO добавить валидацию токенов?
            $sessionToken = $_SESSION['token'] ?? null;
            // если токен установлен и в сессии и в куки и совпадают, то возвращаем пользователя по id из сессии
            if (isset($cookeToken, $sessionToken) && ($cookeToken === $sessionToken) ) {
                return User::findOneBy(type: 'id', subject: $_SESSION['id']) ?? new User();
            }
            // если токен в сессии и куке есть, но они не совпадают, то удаляем оба.
            if (isset($cookeToken, $sessionToken) && ($cookeToken !== $sessionToken)) {
                setcookie('token', '', time() - 86400, '/');
                unset($_SESSION['user'], $_SESSION['token']);
                return new User();
            }
            // если есть только один токен, то сравниваем его с токеном из БД, и пытаемся получить пользователя.
            $tokenOne =  $sessionToken ?? $cookeToken ?? '';
            $session = self::findOneBy('token', $tokenOne);
            if ($session->exist()) {
                return User::findOneBy(type: 'id', subject: $session->getUserId()) ?? new User();
            }
            return new User();
        }

        public function exist() : bool
        {
            return (!empty($this->id) && !empty($this->date_add));
        }

        public function getUserId() : string
        {
            return $this->user_id;
        }
    }
