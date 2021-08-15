<?php

    namespace App\classes\models;

    use App\classes\abstract\AbstractModel;
    use App\classes\Config;

    class Sessions extends AbstractModel
    {
        //        TODO допилить этот класс
        //TODO решить проблему с неисчезающим при пустом обновлении всплывающим алертом о неверности логина или пароля
        // использовать сессию для передачи ошибки и переадресацию на страницу по новой
        protected const TABLE_NAME = 'sessions';
        protected string $user_id, $token;
        protected ?string $date_add = null, $date_last = null;

        public function createNewSession(User $entering) : void
        {
            $this->token = makeToken(64);
            $this->user_id = $entering->getId();
            $this->save();
            setcookie('token', $this->token, time() + 86400, Config::getInstance()->BASE_URL);
            $_SESSION['user'] = $entering->getLogin();
            $_SESSION['id'] = $entering->getId();
            $_SESSION['token'] = $this->token;
            header('Location: ' . Config::getInstance()->BASE_URL);
        }

        /**
         * @throws \App\classes\exceptions\CustomException
         */
        public static function getCurrent() : User
        {
            $cookeToken = $_COOKIE['token'] ?? null; // TODO добавить валидацию токенов?
            $sessionToken = $_SESSION['token'] ?? null;
            // если токен установлен и в сессии и в куки и совпадают, то возвращаем имя пользователя из сессии
            if ( ( $cookeToken && $sessionToken ) && ( $cookeToken === $sessionToken) ) {
                return User::findOneBy(type: 'id', subject: $_SESSION['id']) ?? new User();
            }
            // если токен в сессии и куке есть, но они не совпадают, то удаляем оба.
            if ( ( $cookeToken && $sessionToken ) &&  ($cookeToken !== $sessionToken ) ) {
                unset($_SESSION['user'], $_SESSION['token']);
                setcookie('token', '', time() - 86400, '/');
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
