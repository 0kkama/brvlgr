<?php

    namespace App\classes\controllers\admin;

    use App\classes\abstract\controllers\Controller;
    use App\classes\controllers\Error;
    use App\classes\models\Navigation as NaviModel;
    use App\classes\utility\containers\FormsWithData;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\NavigationBar;
    use App\classes\utility\inspectors\NavigationInspector;
    use App\classes\utility\loggers\LoggerSelector;
    use App\interfaces\InspectorInterface;

    class Navigation extends Controller
    {
        protected NaviModel $navi;
        protected NavigationBar $naviBar;
        protected InspectorInterface $inspector;
        protected static array $fields = ['title', 'url', 'order'];

        public function __invoke()
        {
            $this->id = $this->params['id'] ?: '';
            $action = $this->params['action'] ?: 'show';

            if (method_exists($this, $action)) {
                $this->$action();
            } else {
                Error::deadend(400);
            }
            parent::__invoke();
        }

        public function show(): void
        {
            $this->title = 'Редактирование меню навигации';
            ($this->naviBar = new NavigationBar())->addArray(NaviModel::getAllBy());
            $this->add();
            $this->content = $this->page->assign('naviBar', $this->naviBar)->assign('errors', $this->errors)->assign('navigation',$this->navi)->render('admin/navigation');
        }

        public function add(): void
        {
            $this->navi = new NaviModel();
            ($this->inspector = new NavigationInspector())->setModel($this->navi);
            $this->sendData('добавил');
        }

        public function edit(): void
        {
            $this->title = 'Изменить пункт меню';
            $this->navi = NaviModel::findOneBy('id', $this->id);
            ($this->inspector = new NavigationInspector())->setModel($this->navi);
            if ($this->navi->exist()) {
                $this->sendData('изменил');
            }
            $this->content = $this->page->assign('navi', $this->navi)->assign('errors', $this->errors)->render('admin/navigation_edit');
        }

        public function hide() : void
        {
            $this->modifyNavigation(0, 'скрыл');
        }

        public function regain() : void
        {
            $this->modifyNavigation(1, 'открыл');
        }

        public function private() : void
        {
            $this->modifyNavigation(2, 'сделал приватным');
        }

        public function delete(): void
        {
            $this->navi = NaviModel::findOneBy('id', $this->id);
            if ($this->navi->exist() && $this->navi->delete()) {
                $this->writeAndGo('удалил');
            }
        }

        protected function modifyNavigation(int $status, string $action) : void
        {
            $this->navi = NaviModel::findOneBy('id', $this->id);
            if ($this->navi->exist()) {
                if ($this->navi->setStatus($status)->save()) {
                    $this->writeAndGo($action);
                }
            }
        }

        protected function sendData(string $action) : void
        {
            if ('POST' === $_SERVER['REQUEST_METHOD']) {
                ($forms = new FormsWithData())->extractPostForms(self::$fields, $_POST)->validateForms();
                $this->errors = new ErrorsContainer();
                $this->inspector->setContainer($this->errors)->setForms($forms)->conductInspection();
                $this->navi->setFields($forms);
                if ($this->errors->isEmpty() && $this->navi->save()) {
                    $this->writeAndGo($action);
                }
            }
        }

        protected function writeAndGo(string $action) : void
        {
            $message = 'Администратор: ' . $this->user . " $action пункт меню " .$this->navi->getId() .' ' . $this->navi->getTitle();
            LoggerSelector::publication($message);
            header('Location: /overseer/navigation');
        }
    }
