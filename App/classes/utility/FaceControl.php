<?php

    namespace App\classes\utility;

    use App\classes\models\User;

    class FaceControl
    {
        public static function checkUserRights(User $user, string $userStatus) : bool
        {
            $rights = Config::getInstance()->getAllRights();
            return (isset($rights[$userStatus]) && $user->getRights() >= $rights[$userStatus]);
        }
    }
