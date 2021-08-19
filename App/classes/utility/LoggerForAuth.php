<?php

    namespace App\classes\utility;

    use App\classes\Config;

    class LoggerForAuth
    {
        private string $message;
        private string $path;

        public function __construct(string $message)
        {
            $currentDate = date('Y-m-d');
            $currentTime =  date('H:i:s');
            $this->message = $currentTime . ' - ' . $message;
            $this->path = Config::getInstance()->AUTH_LOG . "$currentDate.log";
        }

        public function write() : void
        {
            var_dump($this->message, $this->path);
            file_put_contents($this->path, $this->message, FILE_APPEND);
        }
    }
