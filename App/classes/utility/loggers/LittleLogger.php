<?php


    namespace App\classes\utility\loggers;
    use App\classes\exceptions\CustomException;
    use Exception;
    use JetBrains\PhpStorm\Pure;


    class LittleLogger
    {
        protected int|string $number;
        protected string $file, $line, $date, $time, $message, $log = '', $type = '', $alert = '', $param = '';
        protected static string $path = __DIR__ . '/../../../logs/errors/';

        #[Pure] protected function __construct(Exception $ex)
        {
            $this->number = $ex->getCode();
            $this->file = $ex->getFile();
            $this->line = $ex->getLine();
            $this->message = $ex->getMessage();
            $this->type = get_class($ex);
            $this->date = date('Y-m-d');
            $this->time = date('H:i:s');

            if ($ex instanceof CustomException) {
                $this->alert = $ex->getAlert();
                $this->param = $ex->getParam();
                $this->type = $ex->getType();
            }
        }

        #[Pure] public static function create(Exception $ex) : LittleLogger
        {
            return new self($ex);
        }

        public function write() : LittleLogger
        {
            $logFile = static::$path . $this->date . '.log';
            $this->log = "$this->time - $this->number - $this->type $this->message $this->param in $this->file line:$this->line";
            error_log("$this->log\n", 3, $logFile);
            return $this;
        }

        public static function errorCatcher(int $errNo, string $errMsg, string $errFile, string $errLine) : void
        {
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            $errPath = __DIR__ . '/../../../logs/errors';

            $msgStr = "$currentTime - $errNo - $errMsg in $errFile line:$errLine";
            error_log("$msgStr\n", 3, "$errPath/$currentDate.log");
            echo 'ERROR!';
        }
    }
