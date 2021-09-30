<?php

    namespace tests\acceptance;


    use AcceptanceTester;

    class FirstCest
    {

        public function frontpageWorks(AcceptanceTester $I)
        {
            $I->amOnPage('/user/login');
            $I->seeInTitle('Войти на сайт');
        }

    //    public function tryToLogIn(AcceptanceTester $I)
    //    {
    //        $I->amOnPage('/user/login');
    //        $I->fillField('Логин','davert');
    //        $I->fillField('Пароль','qwerty');
    //        $I->click('Войти');
    //        $I->see('Неверный логин или пароль ');
    //    }

        public function _before(AcceptanceTester $I)
        {
        }

        // tests
        public function tryToTest(AcceptanceTester $I)
        {
        }
    }
