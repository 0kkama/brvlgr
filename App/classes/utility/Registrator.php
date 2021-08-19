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

        public static function checkUserAbsent(User $user) : void
        {
            if ($user->exist()) {
                header('Location: '. Config::getInstance()->BASE_URL);
                die();
            }
        }



    }
