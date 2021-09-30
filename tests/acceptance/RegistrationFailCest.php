<?php


namespace tests\acceptance;


use \AcceptanceTester;

class RegistrationFailCest
{

    protected array $emptyErr =
        [
            'login' => 'Логин отсутствует или некорректен',
            'firstName' => 'Отсутствует имя',
            'middleName' => 'Отсутствует отчество',
            'lastName' => 'Отсутствует фамилия',
            'email' => 'Не указан почтовый ящик',
            'password1' => 'Пароль отсутствует или некорректен',
            'password2' => 'Необходимо ввести повторный пароль',
        ];

    protected array $invalidErr =
        [
            'email' => 'Некорректное название почтового ящика',
            'login' => 'Некорректный логин',
            'passLength' => 'Длина пароля должна быть от 8 до 30 символов',
            'passMatch' => 'Пароли не совпадают',
        ];

    protected array $repeatErr = [
        'email' => 'Такой почтовый ящик уже используется',
        'login' => 'Подобный логин уже используется',
    ];

    protected array $invalidData = [
        'toShortLogin' => 'Tz',
        'toLongLogin' => 'Tfrndkfiprsdtiprdhtnf',
        'invalidLogin' => 'T<d.d^^#~~>kd&8',
    ];

    protected array $validData = [
        'login' => 'Testlogin',
        'firstName' => 'Тестимя',
        'middleName' => 'Тестотчество',
        'lastName' => 'Тестфамилия',
        'email' => 'testmail@test.com',
        'password1' => '12345678',
        'password2' => '12345678',
    ];

    private string $buttonName = 'enter';
    private string $page = '/user/registration', $logUrl = '/user/login';
    private string $validLogin = 'Testov', $validPass = '12345678';

    public function _before(AcceptanceTester $I)
    {
    }

    // tests

    public function allFieldsEmpty(AcceptanceTester $tester)
    {
        $params = [];
        $tester->amOnPage($this->page);
        $tester->submitForm('#registration-forms', $params);
        foreach ($this->emptyErr as $error) {
            $tester->see($error);
        }
    }

    public function tyrToRegistrAfterLogIn(AcceptanceTester $tester)
    {
        $tester->amOnPage($this->logUrl);
        $tester->fillField('Логин', $this->validLogin);
        $tester->fillField('Пароль', $this->validPass);
        $tester->click($this->buttonName);
        $tester->see($this->validLogin);
        $tester->amOnPage($this->page);
        $tester->seeInTitle('Главная');
    }

    public function emptySecondPass(AcceptanceTester $Me)
    {
        $params = [$this->validData['login'],
                   $this->validData['firstName'],
                   $this->validData['middleName'],
                   $this->validData['lastName'],
                   $this->validData['email'],
                   $this->validData['password1'],
            ];
        $Me->amOnPage($this->page);
        $Me->submitForm('#registration-forms', $params);
        $Me->see($this->emptyErr['password2']);
    }

    public function emptyBouthPass(AcceptanceTester $Me)
    {
        $params = [$this->validData['login'],
                   $this->validData['firstName'],
                   $this->validData['middleName'],
                   $this->validData['lastName'],
                   $this->validData['email'],
            ];

        $Me->amOnPage($this->page);
        $Me->submitForm('#registration-forms', $params);
        $Me->see($this->emptyErr['password1']);
        $Me->see($this->emptyErr['password2']);
    }
}
