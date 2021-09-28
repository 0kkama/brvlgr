<?php

    namespace App\classes\controllers\user\change;

    use App\classes\abstract\controllers\ControllerChanger;
    use App\classes\utility\inspectors\ChangeUserLoginFormsInspector as Inspector;
    use App\classes\utility\View;

    final class Login extends ControllerChanger
    {
        protected static array $subject = ['rus' => 'логин', 'eng' => 'login'];
        protected static array $fields = ['login'];
        protected Inspector $inspector;

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->inspector = new Inspector();
        }

        protected function specificAction() : void
        {
            $this->oldProperty = $this->user->getLogin();
            $this->user->setLogin($this->forms->get('login'))->save();
            $this->newProperty = $this->user->getLogin();
        }
    }
