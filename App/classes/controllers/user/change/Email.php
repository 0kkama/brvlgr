<?php

    namespace App\classes\controllers\user\change;

    use App\classes\abstract\controllers\ControllerChanger;
    use App\classes\utility\inspectors\ChangeUserEmailFormsInspector as Inspector;
    use App\classes\utility\View;

    final class Email extends ControllerChanger
    {
        protected Inspector $inspector;
        protected static array $subject = ['rus' => 'почтовый ящик', 'eng' => 'email'];
        protected static array $fields = ['email'];

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->inspector = new Inspector();
        }

        protected function specificAction() : void
        {
            $this->oldProperty = $this->user->getEmail();
            $this->user->setEmail($this->forms->get('email'))->save();
            $this->newProperty = $this->user->getEmail();
        }
    }
