<?php
    namespace App\classes\abstract\controllers;

    use App\classes\models\User;
    use App\classes\utility\containers\FormsWithData;
    use App\classes\testexamples\UserInspector;
    use App\classes\utility\Registrator;
    use App\classes\utility\View;
    use App\interfaces\InspectorInterface;

    /**
     Controller for inheritance to Registration and Login controllers
     */
    abstract class ControllerEntering extends Controller
    {
//        protected string $title;
        protected User $candidate;
        protected Registrator $registrator;
        protected InspectorInterface $inspector;
        protected FormsWithData $forms;
        protected static array $fields = [];
        protected static string $action = '';

        /**
         * @param array $params
         * @param View $templateEngine
         * @throws \App\classes\exceptions\ExceptionWrapper
         */
        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            Registrator::checkUserAbsent($this->user);
            $this->candidate = new User();
            $this->forms = new FormsWithData();
        }

        protected function entering() : void
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $this->forms->extractPostForms(static::$fields, $_POST);
                $this->forms['checkbox'] = isset($_POST['remember']);
                $this->inspector->setForms($this->forms)->setContainer($this->errors)->setModel(new User())->conductInspection();
                $this->candidate->setFields($this->forms);

                if ($this->errors->isEmpty()) {
                    $action = static::$action;
                    ($this->registrator = new Registrator($this->candidate, $this->forms))->$action();
                }
            }
        }

        public function __invoke()
        {
            $this->entering();
            parent::__invoke();
        }
    }
