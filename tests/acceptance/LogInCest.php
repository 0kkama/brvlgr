<?php

    namespace tests\acceptance;


    use AcceptanceTester;
    use Codeception\Util\Autoload;
    use App\classes\utility\Config;

    class LogInCest
    {
        private array $errors =
            [
                'login'   => 'Введите логин',
                'pass'    => 'Введите пароль',
                'invalid' => 'Неверный логин или пароль ',
            ];

        private string $buttonName = 'enter';
        private string $url = '/user/login';
        private string $validLogin = 'Testov', $validPass = '12345678';

        public function __construct()
        {
//            Autoload::addNamespace('App\classes\utility', __DIR__ . '/../App/classes/utility/');
//            Autoload::load('Config');
//            $this->validLogin = Config::getInstance()->getTestData('author', 'login');
//            $this->validPass = Config::getInstance()->getTestData('author', 'password');
        }

        public function _before(AcceptanceTester $I): void
        {

        }

        public function logInSuccess(AcceptanceTester $I)
        {
            $I->amOnPage($this->url);
            $I->fillField('Логин', $this->validLogin);
            $I->fillField('Пароль', $this->validPass);
            $I->click($this->buttonName);
            $I->see($this->validLogin);
        }

        public function logInEmptyFieldsErrors(AcceptanceTester $I)
        {
            $I->amOnPage($this->url);
            $I->click($this->buttonName);
            $I->see($this->errors['login']);
            $I->see($this->errors['pass']);
        }

        public function logInEmptyPassError(AcceptanceTester $I)
        {
            $I->amOnPage($this->url);
            $I->fillField('Логин', $this->validLogin);
            $I->click($this->buttonName);
            $I->see($this->errors['pass']);
        }

        public function logInEmptyLoginError(AcceptanceTester $I)
        {
            $I->amOnPage($this->url);
            $I->fillField('Пароль', $this->validLogin);
            $I->click($this->buttonName);
            $I->see($this->errors['login']);
        }

        public function logInNotValidError(AcceptanceTester $I)
        {
            $I->amOnPage($this->url);
            $I->fillField('Логин', $this->validLogin);
            $I->fillField('Пароль', $this->validLogin);
            $I->click($this->buttonName);
            $I->see('Неверный логин или пароль ');
        }
    }
