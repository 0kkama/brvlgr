<?php

    namespace App\classes\models;

    use App\classes\abstract\models\Model;
    use App\classes\exceptions\ExceptionWrapper;
    use App\interfaces\UserInterface;
    use App\traits\SetControlTrait;
    use JetBrains\PhpStorm\Pure;

    /**
     * Class User
     * has following personal important methods:
     * <ul>
     * <li><b>findByLogin</b> - return null|object</li>
     * <li><b>CurrentUser</b> - return object containing data of a user by session and cookie or null</li>
     * </ul>
     * @package App\classes\models
     */
    class User extends Model implements UserInterface
    {
        protected const TABLE_NAME = 'users';
        protected string $firstName = '', $middleName = '', $lastName = '', $login = '', $email = '';
        protected ?string $hash = null, $rights = null;

        //                              TODO убрать трейт?

        use SetControlTrait;

        /**
         * Возвращает объект текущего пользователя по кукам и сесси, либо пустой объект User
         * @throws ExceptionWrapper
         */
        public static function getCurrent() : User
        {
            return Sessions::getCurrent();
        }

        public static function checkPassword(string $login, string $password) : User
        {
            $user = self::findOneBy('login', $login);
            if ($user->exist() && password_verify(password: $password, hash: $user->getHash())) {
                return $user;
            }
                return new self();
        }

        /**
         * Return TRUE only if User has NOT empty fields $id and $login
         * @return bool */
        #[Pure] public function __invoke() : bool
        {
            return $this->exist();
        }

        #[Pure] public function exist() : bool
        {
            return (!empty($this->id) && !empty($this->login));
        }

        public function hasUserRights() : bool
        {
            return (!empty($this->id) && ((int) $this->rights >= 1));
        }

        public function hasAdminRights() : bool
        {
            return (!empty($this->id) && ((int) $this->rights === 10));
        }

        public function __toString() : string
        {
            return "$this->login <br> $this->email <br> $this->date";
        }

        //<editor-fold desc="getters =======================">
        /**
         * @return string|null
         */
        public function getLogin() : ?string
        {
            return $this->login;
        }

        /**
         * @return string|null
         */
        public function getHash() : ?string
        {
            return $this->hash;
        }

        /**
         * @return string|null
         */
        public function getRights() : ?string
        {
            return $this->rights;
        }

        /**
         * @return string|null
         */
        public function getEmail() : ?string
        {
            return $this->email;
        }

        public function getFirstName() : string
        {
            return $this->firstName;
        }

        /**
         * @return string
         */
        public function getMiddleName(): string
        {
            return $this->middleName;
        }

        /**
         * @return string
         */
        public function getLastName() : string
        {
            return $this->lastName;
        }

        /**
         * @return string|null
         */

        //</editor-fold>

        //<editor-fold desc="setters ========================">
        /**
         * @param string $email
         */
        public function setEmail(string $email) : User
        {
            $this->email = $email;
            return $this;
        }

        /**
         * @param string $rights
         */
        public function setRights(string $rights) : User
        {
            $this->rights = $rights;
            return $this;
        }

        /**
         * @param string $firstName
         */
        public function setFirstName(string $firstName) : User
        {
            $this->firstName = $firstName;
            return $this;
        }

        /**
         * @param string $middleName
         */
        public function setMiddleName(string $middleName) : User
        {
            $this->middleName = $middleName;
            return $this;
        }

        /**
         * @param string $lastName
         */
        public function setLastName(string $lastName) : User
        {
            $this->lastName = $lastName;
            return $this;
        }

        /**
         * @param string $login
         */
        public function setLogin(string $login) : User
        {
            $this->login = $login;
            return $this;
        }

        /**
         * Temporary password fields for the moment when user make registration
         * @param string|null $pass
         */

        /**
         * @return User
         */
        public function makeHash($password) : User
        {
            $this->hash = password_hash($password, PASSWORD_BCRYPT);
            return $this;
        }
        //</editor-fold>

    }

