<?php
    namespace App\classes\utility;

    use App\classes\Config;
    use App\classes\models\User;
    use Exception;
    use Intervention\Image\ImageManagerStatic as Image;

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
            if ($this->file['size'] === 0) {
                $this->errors->add('Файл не выбран');
            }

            if ($this->file['size'] > 2000000) {
                $this->errors->add('Превышен допустимый размер файла');
            }

            if (!preg_match("`[-A-z0-9_.]+\.jpe?g`", $this->file['name'])) {
                $this->errors->add('Некорректное имя файла. Допустим только jpg-формат!');
            }

            if ((mb_strlen($this->file['name'], "UTF-8") > 225) ) {
                $this->errors->add('Имя файла больше допустимой длины');
            }
            return $this->errors->notEmpty();
        }

        /**
         * Forming string for access logs and uploaded image if there is no errors in checkFile function
         * @return UsersErrors
         * @throws Exception
         */
        public function upload() : UsersErrors
        {
            if (!$this->checkFile()) {
                Image::configure(['driver' => 'imagick']);
                $directoryPath = Config::getInstance()->IMG_PATH;
                $prePath = Config::getInstance()->IMG_PRE;
//                $fileName = $this->file['name'];
                $newFileName = makeToken(16).'.jpg';
                $currentTime =  date('H:i:s');
                $currentDate = date('d-m-Y');
                $userName = $this->user->login;
                $userID = $this->user->id;

                Image::make($this->file['tmp_name'])->resize(200, 200)->save($prePath.$newFileName);
                move_uploaded_file($this->file['tmp_name'], $directoryPath . $newFileName);
                $msgStr =  "$currentTime - Пользователь id $userID логин: $userName загрузил файл $newFileName в $directoryPath\n";
                file_put_contents(Config::getInstance()->AUTH_LOG . "$currentDate.log", $msgStr, FILE_APPEND);
            }
            return $this->errors;
        }
    }

