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
//        protected array $callback = [];

        public function checkUserAbsent(User $user) : void
        {
            if ($user->exist()) {
                header('Location: '. Config::getInstance()->BASE_URL);
                die();
            }
        }

        public function doYourJob(UserErrorsInspector $inspector, array $callback = [])
        {
            $this->inspector = $inspector;
//            if(!empty($callback)) {
//                $this->callback = $callback;
//            }

            $this->checkFields($inspector, $callback);
            $this->inspector->conductInspection($callback);
        }

        public function setFields(array $post, User $candidate) : self
        {
            $keys = array_keys($post);
            $this->fields = extractFields($keys, $post);

            foreach ($this->fields as $index => &$field) {
                if ($index !== 'password1' || $index !== 'password2') {
                    $this->fields[$index] = val($field);
                    $userMethod = 'set' . ucfirst($index);
                    if (method_exists($candidate, $userMethod)) {
                        $candidate->$userMethod($this->fields[$index]);
                    }
                }
            }
            unset($field);

            $candidate->setPasswords($this->fields['password1'], $this->fields['password2']);
            return $this;
        }

        public function checkFields(UserErrorsInspector $inspector, array $callback = []) : self
//        public function checkFields(, array $callback = []) : self
        {
            $this->inspector->conductInspection($callback);
            return $this;
        }

        public function createNewUser(User $candidate) : void
        {
            $candidate->makeHash();
            $candidate->save();
            header('Location: '. Config::getInstance()->BASE_URL);
        }

//        public function loginUser(User $candidate, ErrorsContainer $errors) : void
//        {
//
//            $candidate = User::checkPassword($candidate->, $password);
//            if ($this->candidate->exist()) {
//                (new Sessions())->createNewSession($this->candidate) ;
//            } else {
//                $errors[] = 'Неверный логин или пароль';
//            }
//        }

    }
