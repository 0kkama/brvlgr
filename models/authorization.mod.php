<?php

    use App\classes\models\User;
    use App\classes\Config;


    /**
     * возвращает массив всех пользователей и хэшей их паролей
     * @return array
     */
    function getUsersList() : array
   {
        $users = USERS_LIST;
        return $users;
   }

   /**
    * check existence of user with given login
    * @param string $login is login of presumably user
    * @return true|false
   **/
    function existsUser(string $login) : bool
    {
        $users = getUsersList();
        $haystack = array_column($users, 'login');
        return in_array($login, $haystack, true);
    }

    /**
     * return true if given login exists and given password is valid
     * @param string $login is login of user
     * @param string $password is password (hash) of user
     * @return true|false
    **/
    function checkPassword(string $login, string $password) : bool
    {
        if (!(empty($login) || empty($password))) {
            $user = User::findByLogin($login);
            if (isset($user)) {
                return password_verify($password, $user->getHash());
            }
        }
        return false;
    }

    /**
    * generate random string (token)
     * @param int $length (by default equal 32)
     * @return string $token (random string in hexadecimal representation. Max length of token is 128 bytes)
     **/
    function makeToken ( int $length = 32 ) : string
    {
        if ( $length > 64 ) {
           $token = substr(bin2hex(random_bytes(64)), 0,128);
        } else {
            $token = bin2hex(random_bytes($length));
        }
        return $token;
    }

    /**
     * Возвращает массив с данными, декодированными из json-файла в случае успеха, либо пустой массив.
     * @param string $fileName
     * @return array
     */
    function getFileContent (string $fileName) : array
    {
        $content = file($fileName);
        if ( empty($content) ) {
          return [];
        }

        $wrapper = static function (string $line) : array {
            return json_decode($line,true);
        };
            return array_map($wrapper, $content);
    }


    /**
     * Производит сравнение токенов в сессии и куки. При совпадении токенов возвращает
     * имя вошедшего на сайт пользователя, либо null
     * @param string $fileName
     * @return string|null
     */
    function getCurrentUser(string $fileName) : ?string
    {
        $cookeToken = $_COOKIE['token'] ?? null;
        $sessionToken = $_SESSION['token'] ?? null;
        // если токен установлен и в сессии и в куки, то проверяем их совпадение
        // если совпадают, то возвращаем имя пользователя из сессии
        if ( ( $cookeToken && $sessionToken ) && ( $cookeToken === $sessionToken) )
        {
            return $_SESSION['user'];
        }
        // если токен в сессии и куке есть, но они не совпадают, то удаляем оба.
        if ( ( $cookeToken && $sessionToken ) && ( $cookeToken !== $sessionToken ) )
        {
            unset($_SESSION['user'], $_SESSION['token']);
            setcookie('token', '', time() - 86400, '/');
            return null;
        }
        // если есть только куки-токен, или только сессионный токен, то сравниваем его с токеном из файла (БД)
        // если совпадают, то берём имя пользователя из файла (БД)  и даём соответствующие права.
        $tokenOne = $cookeToken ?? $sessionToken;
        $dbSession = getFileContent( $fileName );
        $haystack = array_column($dbSession, 'user', 'token');
        $user = $haystack[$tokenOne] ?? null;
        // если нет ни куки-токена ни сессионного-токена, то возвращаем null
        // если пользователь получен, то вновь устанавливаем данные в сессию
        if (null !== $user) {
            $_SESSION['user'] = $user;
            $_SESSION['token'] = $tokenOne;
        }

        return $user;
    }

    // функция логирования
    function makeDownloadsLog(string $userName,string $path, string $fileName) : void
    {
        $currentTime =  date('H:i:s');
        $currentDate = date('d-m-Y');
        $msgStr =  "$currentTime - Пользователь $userName загрузил файл $fileName в $path\n";
        file_put_contents(Config::getInstance()->AUTH_LOG_PATH . "$currentDate.log", $msgStr, FILE_APPEND);
//        file_put_contents('/home/proletarian/NBProj/profit/resources/logs/auth/11-01-2021.log', $msgStr, FILE_APPEND);
    }

