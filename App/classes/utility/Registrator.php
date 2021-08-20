<?php

    namespace App\classes\utility;

    use App\classes\Config;
    use App\classes\models\Sessions;
    use App\classes\models\User;

    class Registrator
    {
        protected array $fields;
        protected FormsWithData $forms;
        protected UserErrorsInspector $inspector;
        protected User $candidate;
        protected Sessions $sessions;
        protected array $callback = [];

        public static function checkUserAbsent(User $user) : void
        {
            if ($user->exist()) {
                header('Location: '. Config::getInstance()->BASE_URL);
                die();
            }
        }

        public function createNewUser(User $candidate, FormsWithData $forms) : void
        {
            $this->candidate = $candidate;
            $this->forms = $forms;
            $this->candidate->makeHash($this->forms->get('password1'));
            $this->candidate->save();
            (new Sessions())->createNewSession($this->candidate, $this->forms->get('checkbox'));
            (new LoggerForAuth('Зарегистрирован пользователь '. $this->candidate))->write();
            header('Location: '. Config::getInstance()->BASE_URL);
        }

        public function loginUser(User $candidate, FormsWithData $forms) : void
        {
            $this->candidate = $candidate;
            $this->forms = $forms;
            $this->candidate = User::findOneBy('login', $this->forms->get('login'));
            (new Sessions())->createNewSession($this->candidate, $this->forms->get('checkbox'));
            (new LoggerForAuth('Пользователь ' . $this->candidate->getLogin() . 'вошёл в систему'))->write();
        }

    }
