<?php
    namespace App\classes;

    class Uploader
    {
        private $file;
        private $errorStatus;

        public function __construct($fieldName)
        {
            $this->file = $fieldName ?? [];
            $this->errorStatus = $this->isUploaded($this->file);
        }

        private function isUploaded($fileName) : string
        {
            if ( empty($fileName) ) {
                return '';
            }

            $errors = [];
            $errMssg = '';

            if ( $fileName['size'] === 0 ) {
                $errors[] = 'Файл не выбран';
                return $errors[0];
            }

            if ( $fileName['size'] > 2000000 ) {
                $errors[] = 'Превышен допустимый размер файла';
                return $errors[0];
            }

            if ( (preg_match("`^[-0-9A-Z_\.]\.jpe?g$`i", $fileName['name'])) ) {
                $errors[] = 'Некорректное расширение или имя файла. Допустим только jpg-формат!';
            }

            if ( (mb_strlen($fileName['name'], "UTF-8") > 225) ) {
                $errors[] = 'Имя файла больше допустимой длины';
            }

            if ( $errors !== [] ) {
                foreach ($errors as $error) {
                    $errMssg .= $error;
                    $errMssg .= "<br>";
                }
            }

            return $errMssg;
        }

        public function upload(string $userName) : void
        {
            if ( empty($this->errorStatus) ) {
                $directoryPath = Config::getInstance()->PATH_FOR_IMG;
                $fileName = $this->file['name'];
                $currentTime =  date('H:i:s');
                $currentDate = date('d-m-Y');

                move_uploaded_file($this->file['tmp_name'], $directoryPath . $this->file['name']);
                $msgStr =  "$currentTime - Пользователь $userName загрузил файл $fileName в $directoryPath\n";
                file_put_contents(Config::getInstance()->AUTH_LOG_PATH . "$currentDate.log", $msgStr, FILE_APPEND);
            }
        }

        public function showErrorStatus() : ?string
        {
            if ( !empty($this->errorStatus) ) {
                return $this->errorStatus;
            }
            return null;
        }
    }
