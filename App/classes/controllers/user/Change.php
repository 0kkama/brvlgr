<?php

    namespace App\classes\controllers\user;

    use App\classes\abstract\controllers\ControllerSelector;
    use App\classes\controllers\Error;
    use App\classes\models\User;
    use App\classes\utility\FaceControl;
    use App\classes\utility\View;

    class Change extends ControllerSelector
    {
        private string $path = 'App\classes\controllers\user\change\\';
        private string $action = '';

        public function action(): void
        {
            if (!FaceControl::checkUserRights(User::getCurrent(), 'user')) {
                Error::deadend(403, 'Необходима авторизация');
            }

            $this->choseAction();
            $controller = $this->path . $this->action;
            if (class_exists($controller)) {
                (new $controller($this->params, new View))();
            } else {
                Error::deadend(400, 'Неизвестное действие');
            }
        }

        private function choseAction(): void
        {
            $key = $this->params['params']['property'] ?? 'account';
            switch ($key) {
                case 'login':
                    $this->action = 'Login';
                    break;
                case 'pass':
                    $this->action = 'Password';
                    break;
                case 'email':
                    $this->action = 'Email';
                    break;
                case 'fullname':
                    $this->action = 'FullName';
                    break;
                case 'account':
                    $this->action = 'Account';
                    $this->path = 'App\classes\controllers\user\\';
                    break;
                default:
                    Error::deadend(404);
            }
        }
    }
