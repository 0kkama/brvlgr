<?php

    namespace App\classes\controllers;

    use App\classes\abstract\controllers\ControllerSelector;
    use App\classes\models\User;
    use App\classes\utility\View;

    class AdminSelector extends ControllerSelector
    {
        protected User $admin;

        public function action() : void
        {
            $user = User::getCurrent();
            if (!$user->hasAdminRights()) {
                Error::deadend(403, 'Нет прав доступа');
            }

            $class = ($this->params['controller'] === 'overseer') ? 'AdminMenu' : ucfirst($this->params['controller']);
            $controller = 'App\classes\controllers\admin\\' . $class;
            if (class_exists($controller)) {
                (new $controller($this->params, new View))();
            } else {
                Error::deadend(400);
            }
        }
    }
