<?php

    namespace App\classes\models;

    use App\classes\abstract\Model;
    use App\classes\Db;
    use App\classes\exceptions\ExceptionWrapper;
    use App\classes\exceptions\FileException;
    use App\classes\exceptions\CustomException;
    use App\classes\utility\ErrorsContainer;
    use App\interfaces\HasIdInterface;
    use App\interfaces\UserInterface;
    use App\traits\GetSetTrait;
    use App\traits\SetControlTrait;
    use JetBrains\PhpStorm\Pure;
    use JsonException;

    /**
     * Class User
     * has following personal important methods:
     * <ul>
     * <li><b>findByLogin</b> - return null|object</li>
     * <li><b>CurrentUser</b> - return object containing data of a user by session and cookie or null</li>
     * </ul>
     * @package App\classes\models
     */
    class User extends Model implements UserInterface
    {
        protected const TABLE_NAME = 'users';
        protected string $firstName = '', $middleName = '', $lastName = '', $login = '', $hash = '', $email = '', $rights = '';
        protected ?string $pass = null;
        protected static array $checkList = ['checkEmail', 'checkLogin', 'checkHash'];
        protected static array $errorsList =
            [
                ':login' => 'Логин отсутствует или некорректен',
                ':firstName' => 'Отсутствует имя',
                ':middleName' => 'Отсутствует отчество',
                ':lastName' => 'Отсутствует фамилия',
                ':email' => 'Не указан почтовый ящик',
                ':hash' => 'Пароль отсутствует или некорректен',
//                TODO не забыть заменить hash
            ];

        use SetControlTrait;

        /**
         * Возвращает данные текущего пользователя по кукам и сесси, либо null
         * @param string $sessionFile
         * @return User
         * @throws ExceptionWrapper
         */
//        TODO обращение к сессии и куки в модели - плохая практика. исправить?
        public static function getCurrent(string $sessionFile) : User
        {
            $cookeToken = $_COOKIE['token'] ?? null; // TODO добавить валидацию токенов?
            $sessionToken = $_SESSION['token'] ?? null;
            // если токен установлен и в сессии и в куки, то проверяем их совпадение
            // если совпадают, то возвращаем имя пользователя из сессии
            if ( ( $cookeToken && $sessionToken ) && ( $cookeToken === $sessionToken) ) {
                return self::findOneBy(type: 'login', subject: $_SESSION['user']) ?? new self;
            }
            // если токен в сессии и куке есть, но они не совпадают, то удаляем оба.
            if ( ( $cookeToken && $sessionToken ) &&  ($cookeToken !== $sessionToken ) ) {
                unset($_SESSION['user'], $_SESSION['token']);
                setcookie('token', '', time() - 86400, '/');
                return new self;
            }
            // если есть только куки-токен, или только сессионный токен, то сравниваем его с токеном из файла сессий (БД)
            // если совпадают, то берём имя пользователя из файла сессий (БД) и даём соответствующие права.
            $tokenOne =  $sessionToken ?? $cookeToken;

//            TODO исправить работу исключения или иным способом решить проблему получения пользователя для заглушки-error
//              ошибка при парсинге sessions.json способна остановить работу всего сайта из-за того, что попытка получения
//              данных о текущем пользователе происходит на каждом из контроллеров
            try {
                $dbSession = getFileContent($sessionFile);
            } catch (JsonException $e) {
               (new ExceptionWrapper('Критическая ошибка на сервере. Сообщите администратору!', 500, $e, false))->throwIt();
            }

            $haystack = array_column($dbSession, 'user', 'token');
            $userName = $haystack[$tokenOne] ?? null;
            // если нет ни куки-токена ни сессионного-токена, то возвращаем null
            // если пользователь получен, то вновь устанавливаем данные в сессию
            if (null !== $userName) {
                $_SESSION['user'] = $userName;
                $_SESSION['token'] = $tokenOne;
                return self::findOneBy(type: 'login', subject: $userName) ?? new self;
            }
            return new self;
        }

        public function checkPassword(string $password) : ?User
        {
            $user = self::findOneBy('login', $this->login);
            if (isset($user) && password_verify(password: $password, hash: $user->getHash())) {
                return $user;
            }
                return null;
        }

        /**
         * Return TRUE only if User has NOT empty fields $id and $login
         * @return bool */
        #[Pure] public function __invoke() : bool
        {
            return $this->exist();
        }

        #[Pure] public function exist() : bool
        {
            return (!empty($this->id) && !empty($this->login));
        }

        public function __toString() : string
        {
            return "$this->login <br> $this->email <br> $this->date";
        }

        //<editor-fold desc="======================= getters">
        public function getErrors() : ErrorsContainer
        {
             return $this->errors;
        }

        /**
         * @return string|null
         */
        public function getLogin() : ?string
        {
            return $this->login;
        }

        /**
         * @return string
         */
        public function getHash() : ?string
        {
            return $this->hash;
        }

        /**
         * @return string|null
         */
        public function getId() : ?string
        {
            return $this->id;
        }

        /**
         * @return string|null
         */
        public function getRights() : ?string
        {
            return $this->rights;
        }

        /**
         * @return string|null
         */
        public function getEmail() : ?string
        {
            return $this->email;
        }

        /**
         * @return string|null
         */
        public function getPass() : ?string
        {
            return $this->pass;
        }
        //</editor-fold>

        //<editor-fold desc="======================= setters">
        /**
         * @param string $email
         */
        public function setEmail(string $email) : User
        {
            $this->email = $email;
            return $this;
        }

        /**
         * @param string $rights
         */
        public function setRights(string $rights) : User
        {
            $this->rights = $rights;
            return $this;
        }

        /**
         * @param string $firstName
         */
        public function setFirstName(string $firstName) : User
        {
            $this->firstName = $firstName;
            return $this;
        }

        /**
         * @param string $middleName
         */
        public function setMiddleName(string $middleName) : User
        {
            $this->middleName = $middleName;
            return $this;
        }

        /**
         * @param string $lastName
         */
        public function setLastName(string $lastName) : User
        {
            $this->lastName = $lastName;
            return $this;
        }

        /**
         * @param string $login
         */
        public function setLogin(string $login) : User
        {
            $this->login = $login;
            return $this;
        }

        /**
         * Temporary password fields for the moment when user make registration
         * @param string|null $pass
         */
        public function setPassword(?string $pass) : User
        {
            $this->pass = $pass;
            return $this;
        }

        /**
         * @param string $password
         * @return User
         */
        public function setHash(string $password) : User
        {
            $this->hash = password_hash($password, PASSWORD_BCRYPT);
            $this->pass = null;
            return $this;
        }
        //</editor-fold>
    }

