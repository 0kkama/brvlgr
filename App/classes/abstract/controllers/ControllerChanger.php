<?php

    namespace App\classes\abstract\controllers;

    use App\classes\utility\containers\ErrorsContainer as Errors;
    use App\classes\utility\containers\FormsForUserData as Forms;
    use App\classes\utility\loggers\LoggerSelector;
    use App\classes\utility\View;

    abstract class ControllerChanger extends Controller
    {
        protected Forms $forms;
        protected string $oldProperty = '', $newProperty = '', $userIdentity = '', $pattern = '';
        protected static array $subject = ['rus' => '', 'eng' => ''];
        protected static array $fields = [];

        public function __construct(array $params, View $templateEngine)
        {
            parent::__construct($params, $templateEngine);
            $this->forms = new Forms();
            $this->errors = new Errors();
            $this->pattern = 'users/change/' . static::$subject['eng'];
            $this->title = 'Сменить ' . static::$subject['rus'];
        }

        public function __invoke()
        {
            $this->changeProperty();
            $this->content = $this->page->assign('forms', $this->forms)->assign('errors', $this->errors)->assign('user', $this->user)->render($this->pattern);
            parent::__invoke();
        }

        abstract protected function specificAction();

        protected function changeProperty(): void
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                $this->forms->extractPostForms(static::$fields, $_POST)->validateForms();
                $this->errors = new Errors();
                $this->inspector->setContainer($this->errors)->setForms($this->forms)->setModel($this->user)->conductInspection();
                if ($this->errors->isEmpty()) {
                    $this->userIdentity = $this->user->getId() . ' - ' . $this->user->getLogin();

                    $this->specificAction();

                    $this->writeAndGo(static::$subject['rus']);
                }
            } else {
                $this->forms->extractDataFrom($this->user, static::$fields);
            }
        }

        protected function writeAndGo(string $subject) : void
        {
            $message = "Пользователь {$this->userIdentity} сменил $subject {$this->oldProperty} на {$this->newProperty}";
            LoggerSelector::authentication($message);
            header("Location: /user/account");
        }
    }
