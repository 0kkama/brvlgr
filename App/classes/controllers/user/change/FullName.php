<?php

    namespace App\classes\controllers\user\change;

    use App\classes\abstract\controllers\ControllerChanger;
    use App\classes\utility\inspectors\ChangeUserFullNameFormsInspector as Inspector;
    use App\classes\utility\View;

    final class FullName extends ControllerChanger
    {
        protected Inspector $inspector;
        protected static array $fields = ['firstName', 'middleName', 'lastName'];
        protected static array $subject = ['rus' => 'ФИО', 'eng' => 'fullname'];

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->inspector = new Inspector();
        }

        protected function specificAction(): void
        {
            $this->oldProperty = $this->user->getFullName();
            foreach (self::$fields as $field) {
                $setter = 'set' . my_mb_ucfirst($field);
                $this->user->$setter($this->forms->get($field));
            }
            $this->user->save();
            $this->newProperty = $this->user->getFullName();
        }
    }
