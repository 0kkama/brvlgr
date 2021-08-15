<?php

    namespace App\classes\utility;

    use App\classes\Config;
    use App\classes\models\User;

    class Registrator
    {
        protected array $fields;

        public function checkUserAbsent(User $user) : void
        {
            if ($user->exist()) {
                header('Location: '. Config::getInstance()->BASE_URL);
                die();
            }
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

        public function checkFields(UserErrorsInspector $inspector) : self
        {
            $inspector->conductInspection();
            return $this;
        }

        public function createNewUser(User $candidate) : void
        {
                $candidate->makeHash();
                $candidate->save();
                header('Location: '. Config::getInstance()->BASE_URL);
        }

    }
