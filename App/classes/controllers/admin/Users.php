<?php

    namespace App\classes\controllers\admin;

    use App\classes\abstract\controllers\Controller;
    use App\classes\controllers\Error;
    use App\classes\models\User as UserModel;
    use App\classes\utility\Config;
    use App\classes\utility\loggers\LoggerSelector;

    class Users extends Controller
    {
        protected UserModel $model;
        protected array $users;
//        сделать модератором

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
        public function show()
        {
            $this->title = 'Список пользователей';
            $this->users = UserModel::getAllBy();
            $this->content = $this->page->assign('users', $this->users)->assign('errors', $this->errors)->render('admin/users_list');
        }

        public function regain() : void
        {
            $this->modifyUser(2, 'восстановил');
        }

        public function ban() : void
        {
            $this->modifyUser(1, 'забанил');
        }

        protected function modifyUser(int $status, string $action) : void
        {
            $this->model = UserModel::findOneBy('id', $this->id);
            $forbiddenLvl = Config::getInstance()->getRightsLvl('overseer');
            if ($this->model->exist() && $this->model->getRights() !== $forbiddenLvl) {
                if ($this->model->setRights($status)->save()) {
                    $this->writeAndGo($action);
                }
            }
        }

        protected function writeAndGo(string $action) : void
        {
            $message = 'Администратор: ' . $this->user->getId() . ' ' . $this->user->getLogin() . " $action пользователя " . $this->model->getId() . ' ' . $this->model->getLogin();
            LoggerSelector::publication($message);
            header('Location: /overseer/users');
        }
    }
