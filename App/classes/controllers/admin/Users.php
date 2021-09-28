<?php

    namespace App\classes\controllers\admin;

    use App\classes\abstract\controllers\Controller;
    use App\classes\controllers\Error;
    use App\classes\models\User as UserModel;
    use App\classes\utility\Config;
    use App\classes\utility\loggers\LoggerSelector;

    /**
     * this class can change rights of users in administration panel.
     * there is several rights types and each next one includes the rights of the previous one:
     * <ul>
     * <li><b>ban</b> - user is banished and has no rights like an unregistered person.</li>
     * <li><b>user</b> - person has base rights after registration like comments of articles etc.</li>
     * <li><b>author</b> - person has user rights and also can create articles</li>
     * <li><b>moder</b> - has author rights and also can approve, edit or delete articles of other authors</li>
     * <li><b>admin</b> - has moder rights and also can manage rights all other users (except main admin)</li>
     * </ul>
     */
    class Users extends Controller
    {
        protected UserModel $model;
        protected array $users;
        protected static array $actions =
        [
            'ban' => ['right' => 1, 'message' => 'забанил пользователя'],
            'regain' => ['right' => 2, 'message' => 'дал пользовательские права'] ,
            'author' => ['right' => 3, 'message' => 'дал авторские права'] ,
            'moder' => ['right' => 5, 'message' => 'дал права модератора'] ,
            'admin' => ['right' => 9, 'message' => 'дал права администратора'] ,
        ];

        public function __invoke()
        {
            $this->id = $this->params['id'] ?: '';
            $action = $this->params['action'] ?: 'show';

            if (method_exists($this, $action)) {
                $this->$action();
            } elseif (isset(self::$actions[$action])) {
                $this->modifyUser(self::$actions[$action]['right'], self::$actions[$action]['message']);
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

        /**
         * Method changes the rights of any specified user (exception of the main administrator - overseer)
         * @param int $status
         * @param string $action
         * @throws \App\classes\abstract\exceptions\CustomException
         */
        protected function modifyUser(int $status, string $action) : void
        {
            $this->model = UserModel::findOneBy('id', $this->id);
            $forbiddenLvl = Config::getInstance()->getRightsLvl('overseer');
            if ($this->model->exist() && $this->model->getRights() !== $forbiddenLvl) {
                if ($this->model->setRights($status)->save()) {
                    $this->writeAndGo($action);
                } else {
//                    todo throw new exception
                }
            } else {
                Error::deadend(403, 'Попытка недопустимого действия');
            }
        }

        protected function writeAndGo(string $action) : void
        {
            $message = 'Администратор: ' . $this->user->getId() . ' ' . $this->user->getLogin() . " $action " . $this->model->getId() . ' ' . $this->model->getLogin();
            LoggerSelector::publication($message);
            header('Location: /overseer/users');
        }
    }
