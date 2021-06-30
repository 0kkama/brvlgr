<?php
    namespace App\classes;

    use App\classes\utility\UsersErrors;
    use App\classes\models\User;

    /**
     * Class Uploader
     * @package App\classes
     */
    class Uploader
    {
        protected array $file;
        protected User $user;
        protected UsersErrors $errors;

        public function __construct(array $file, User $user)
        {
            $this->file = $file ?? [];
            $this->user = $user;
            $this->errors = new UsersErrors();
        }

        /**
         * check correctness file by size and name
         * @return bool
         */
        protected function checkFile() : bool
        {
            $this->errors->add('Допустим только jpg-формат!');

            if ($this->file['size'] === 0) {
                $this->errors->add('Файл не выбран');
            }

            if ($this->file['size'] > 2000000) {
                $this->errors->add('Превышен допустимый размер файла');
            }

            if (preg_match("`^[-0-9A-Z_\.]\.jpe?g$`i", $this->file['name'])) {
                $this->errors->add('Некорректное расширение или имя файла. Допустим только jpg-формат!');
            }

            if ((mb_strlen($this->file['name'], "UTF-8") > 225) ) {
                $this->errors->add('Имя файла больше допустимой длины');
            }
            return $this->errors->__invoke();
        }

        /**
         * Forming string for access logs and uploaded image if there is no errors in checkFile function
         * @return \App\classes\UsersErrors
         */
        public function upload() : UsersErrors
        {
            if (!$this->checkFile()) {
                $directoryPath = Config::getInstance()->IMG_PATH;
                $fileName = $this->file['name'];
                $currentTime =  date('H:i:s');
                $currentDate = date('d-m-Y');
                $userName = $this->user->login;
                $userID = $this->user->id;

                move_uploaded_file($this->file['tmp_name'], $directoryPath . $this->file['name']);
                $msgStr =  "$currentTime - Пользователь id $userID логин: $userName загрузил файл $fileName в $directoryPath\n";
                file_put_contents(Config::getInstance()->AUTH_LOG . "$currentDate.log", $msgStr, FILE_APPEND);
            }

            return $this->errors;
        }
    }

