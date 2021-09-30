<?php
    namespace tests\acceptance;
    use \AcceptanceTester;

    class ArticleCest
    {
        protected array $emptyErr =
            [
                'title' => 'Отсутствует заголовок',
                'text' => 'Отсутствует текст статьи',
            ];

        private string $loginButton = 'enter', $sendButton = 'send', $url = '/article/create';
        private string $authorLogin = 'Testov', $authorPass = '12345678';
        private string $userLogin = 'Sery', $userPass = '12345678';

        public function _before(AcceptanceTester $tester)
        {

        }

        // tests
        public function tryCreateNoLogIn(AcceptanceTester $tester) : void
        {
            $tester->amOnPage($this->url);
            $tester->seeInTitle('403 Forbidden');
        }

        public function tryCreateNoRights(AcceptanceTester $tester) : void
        {
            $this->logIn($tester, $this->userLogin, $this->userPass);
            $tester->amOnPage($this->url);
            $tester->see('Sery');
            $tester->seeInTitle('403 Forbidden');
        }

        public function tryCreateEmptyArticle(AcceptanceTester $tester) : void
        {
            $this->logIn($tester, $this->authorLogin, $this->authorPass);
            $tester->amOnPage($this->url);
            $tester->submitForm('#article-forms', []);
            $tester->see($this->emptyErr['title']);
            $tester->see($this->emptyErr['text']);
        }

        protected function logIn(AcceptanceTester $tester, $login, $pass):  void
        {
            $tester->amOnPage('user/login');
            $tester->fillField('Логин', $login);
            $tester->fillField('Пароль', $pass);
            $tester->click($this->loginButton);
            $tester->see($login);
        }
    }
