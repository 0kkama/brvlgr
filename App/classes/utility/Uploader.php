<?php
    namespace App\classes\utility;

    use App\classes\Config;
    use App\classes\models\User;
    use Exception;
    use Intervention\Image\ImageManagerStatic as Image;
    use App\classes\utility\LoggerForAuth as AuthLog;

    /**
     * Class Uploader
     * @package App\classes
     */
    class Uploader
    {
        protected array $file, $list;
        protected User $user;
        protected ErrorsContainer $errors;
        protected string $origPath, $prePath, $newFileName;

        public function __construct(array $file, User $user)
        {
            $this->file = $file ?? [];
            $this->user = $user;
            $this->errors = new ErrorsContainer();
            $this->origPath = Config::getInstance()->IMG_PATH;
            $this->prePath = Config::getInstance()->IMG_PRE;
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
         * @throws Exception
         */
        protected function checkName() : void
        {
            $origPath = Config::getInstance()->IMG_PATH;
            $this->list = scandir($origPath, SCANDIR_SORT_DESCENDING);
            array_pop($this->list);
            array_pop($this->list);
            $haystack = array_flip($this->list);

            if (isset($haystack[$this->newFileName])) {
                $this->newFileName = makeToken(16).'.jpg';
            }
        }

        /**
         * Forming string for access logs and uploaded image if there is no errors in checkFile function
         * @return ErrorsContainer
         * @throws Exception
         */
        public function upload() : ErrorsContainer
        {
            if (!$this->checkFile()) {
                Image::configure(['driver' => 'imagick']);

                $this->newFileName = makeToken(16).'.jpg';
                $userName = $this->user->login;
                $userID = $this->user->getId();

                $this->checkName();

                Image::make($this->file['tmp_name'])->resize(200, 200)->save($this->prePath . $this->newFileName);
                move_uploaded_file($this->file['tmp_name'], $this->origPath . $this->newFileName);
                $message =  "Пользователь id $userID логин: $userName загрузил jpg файл $this->newFileName в галерею\n";
                (new AuthLog($message))->write();
            }
            return $this->errors;
        }
    }

