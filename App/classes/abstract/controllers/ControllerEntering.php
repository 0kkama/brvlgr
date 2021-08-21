<?php
    namespace App\classes\abstract\controllers;

    use App\classes\abstract\Controller;
    use App\classes\models\User;
    use App\classes\utility\FormsWithData;
    use App\classes\utility\Registrator;
    use App\classes\utility\UserErrorsInspector;
    use App\classes\View;

    abstract class ControllerEntering extends Controller
    {
        protected string $title;
        protected User $candidate;
        protected Registrator $registrator;
        protected UserErrorsInspector $inspector;
        protected FormsWithData $forms;
        protected static array $checkList = [];
        protected static array $errorsList = [];
        protected static string $action = '';

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            ($this->registrator = new Registrator())::checkUserAbsent($this->user);
            $this->candidate = new User();
            $this->forms = new FormsWithData();
        }

        protected function entering() : void
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $this->forms->extractPostForms(array_keys(static::$errorsList), $_POST);
                $this->forms['checkbox'] = isset($_POST['remember']);
                $this->inspector = new UserErrorsInspector($this->forms, $this->errors, static::$errorsList);
                $this->inspector->conductInspection(static::$checkList);
                $this->candidate->setFields($this->forms);

                if ($this->errors->isEmpty()) {
                    $action = static::$action;
                    $this->registrator->$action($this->candidate, $this->forms);
                }
            }
        }

        public function __invoke()
        {
            $this->entering();
            parent::__invoke();
        }
    }
