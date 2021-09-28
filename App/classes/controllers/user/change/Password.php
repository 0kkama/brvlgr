<?php

    namespace App\classes\controllers\user\change;

    use App\classes\abstract\controllers\ControllerChanger;
    use App\classes\utility\inspectors\ChangeUserPassFormsInspector as Inspector;
    use App\classes\utility\View;

    final class Password extends ControllerChanger
    {
        protected static array $fields = ['password1', 'password2'];
        protected static array $subject = ['rus' => 'пароль', 'eng' => 'password'];
        protected Inspector $inspector;

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->inspector = new Inspector();
        }

        protected function specificAction(): void
        {
            $this->oldProperty = 'старый';
            $this->user->makeHash($this->forms->get('password1'))->save();
            $this->newProperty = 'новый';
        }
    }
