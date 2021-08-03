<?php

    namespace App\classes\controllers;

    use App\classes\abstract\Controller;
    use App\classes\Config;
    use App\classes\View;

    class Registration extends Controller
    {
        protected string $title = 'Регистрация', $content = 'PAGE NOT FOUND!';

//        public function __construct(array $params, View $templateEngine)
//        {
//            parent::__construct($params, $templateEngine);
//            $this->title = "Войти на сайт";
//            $this->relocation = Config::getInstance()->BASE_URL;
//        }

        public function checkUser()
        {
            if ($this->user->exist()) {
                $url = Config::getInstance()->BASE_URL;
                header("Location: $url");
                die();
            }
        }

        public function checkForms()
        {
//          TODO не забыть удалить пар1 и пар2
//          TODO проверить совпадение 1-го и 2 пароля в контроллере

//            if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $arr = ['login' => 'zurab', 'firstName' => 'Олег', 'middleName' => 'Петрович',
                    'lastName' => 'Давыдов','email' => 'mydead@dsf.com'];
//                $keys = array_keys($_POST);
                $keys = array_keys($arr);
                $fields = extractFields($keys,$arr);
                var_dump($fields);
//                $fields = extractFields($keys,$_POST);

                foreach ($fields as $index => &$field) {
                    if ($index !== 'password' || $index !== 'password2') {
                        $fields[$index] = val($index);
                        $userMethod = 'set' . ucfirst($index);
                        $this->$userMethod($fields[$index]);
                    }
                }
                unset($field);

            }
//            $this->user->setLogin($login);
//            $this->user->setFirstName($firstName);
//            $this->user->setMiddleName($middleName);
//            $this->user->setLastName($lastName);
//            $this->user->setEmail($email);
//            $this->user->setPassword($password);
//        }

        public function __call($method, $arg)
        {
//            var_dump($arg);
            if (method_exists($this->user, $method)) {
                $this->user->$method($arg[0]);
            }
        }

        public function test()
        {
            $this->checkForms();
            return $this->user;
        }
    }
//TODO -
// 1. получить данные форм
// 2. проверить данные по форме и через БД
//    - сравнить два пароля
//    - валидация данных и запросы к бд
//    -
    // 3. сохранить данные во временном хранилище
    // 4. отправить письмо с ключом на почтовый ящик
    // 5. после ввода ключа - пользователь является подтвержденным
