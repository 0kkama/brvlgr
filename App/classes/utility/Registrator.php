<?php

    namespace App\classes\utility;

    use App\classes\models\Sessions;
    use App\classes\models\User;
    use App\classes\models\UserSessions;
    use App\classes\utility\containers\FormsWithData;
    use App\classes\utility\loggers\LoggerSelector;

    class Registrator
    {
        protected array $fields;
        protected FormsWithData $forms;
        protected UserInspector $inspector;
        protected User $candidate;
        protected Sessions $sessions;
        protected UserSessions $userSessions;
        protected array $callback = [];

        public function __construct(User $candidate, FormsWithData $forms)
        {
            $this->candidate = $candidate;
            $this->forms = $forms;
        }

        public static function checkUserAbsent(User $user) : void
        {
            if ($user->exist()) {
                header('Location: '. Config::getInstance()->BASE_URL);
                die();
            }
        }

        public function createNewUser() : void
        {
            $this->candidate->makeHash($this->forms->get('password1'))->save();
            $this->writeSession();
            LoggerSelector::authentication('Зарегистрирован пользователь ' . $this->candidate->getLogin());
            header('Location: '. Config::getInstance()->BASE_URL);
        }

        public function loginUser() : void
        {
            $this->candidate = User::findOneBy('login', $this->forms->get('login'));
            $this->writeSession();
            LoggerSelector::authentication('Пользователь ' . $this->candidate->getLogin() . ' вошёл в систему');
            header('Location: '. Config::getInstance()->BASE_URL);
        }

        protected function writeSession() : void
        {
            ($this->sessions = new Sessions())->createNewSession($this->forms->get('checkbox'));
            ($this->userSessions = new UserSessions())->setUserId($this->candidate->getId())->setSessId($this->sessions->getId())->save();
        }

    }
