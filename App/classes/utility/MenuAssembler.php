<?php

    namespace App\classes\utility;


    use App\classes\models\Navigation;
    use App\classes\models\User;
    use App\classes\utility\containers\NavigationBar;

    /**
     * Assembly and returns a collection of menu items depending on the user's rights
     */
    class MenuAssembler
    {
        protected NavigationBar $menu;
        protected User $user;
        protected array $array;
        protected static array $build = [
            'noname'   => ['noname', 'main'],
            'user'     => ['user', 'main'],
            'author'   => ['user', 'author', 'main'],
            'moder'    => ['user', 'author', 'admin', 'main'],
            'admin'    => ['user', 'author', 'admin', 'main'],
            'overseer' => ['user', 'author', 'admin', 'main'],
        ];

        public static function assemblyMenu(User $user): array
        {
            $rights = array_flip(Config::getInstance()->getAllRights());
            if ($user->exist()) {
                $userR = $user->getRights();
                $userS = $rights[$userR];
            } else {
                $userS = 'noname';
            }
            return Navigation::getAllInBy('status', self::$build[$userS], 'order');
        }
    }
