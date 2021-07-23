<?php


    namespace App\classes\utility;


    use App\classes\exceptions\ExceptionWrapper as Wrapper;
    use App\classes\exceptions\FullException;
    use App\classes\utility\SendMail;
    use Exception;

    /**
     * Class LoggerForExceptions
     * Put information about exceptions into logs
     * @package App\classes\utility
     */
    class LoggerForExceptions
    {
        protected string $date, $time, $logFile, $logMessage;
        protected Exception $ex;
        protected static string $path = __DIR__ . '/../../../logs/errors/';

        public function __construct(Exception $ex)
        {
            $this->ex = $ex;
            $this->date = date('Y-m-d');
            $this->time = date('H:i:s');
            $this->logFile = static::$path . $this->date . '.log';
        }

        public function write() : void
        {
            if ($this->ex instanceof Wrapper) {
                $this->logMessage = "$this->time - {$this->ex->getCode()} - {$this->ex->getType()} {$this->ex->getMessage()} in {$this->ex->getFile()} line:{$this->ex->getLine()}";
            } elseif ($this->ex instanceof FullException) {
                $this->logMessage = "$this->time - {$this->ex->getCode()} - {$this->ex->getType()} {$this->ex->getMessage()} in {$this->ex->getFile()} line:{$this->ex->getLine()}";
            } else {
                $this->logMessage = "$this->time - {$this->ex->getCode()} - {$this->ex->getMessage()} in {$this->ex->getFile()} line:{$this->ex->getLine()}";
            }
                error_log("$this->logMessage\n", 3, $this->logFile);

            if ($this->ex->isCritical()) {
                SendMail::send($this->date."(Сообщение: {$this->ex->getAlert()} код: {$this->ex->getNumber()})", $this->logMessage);
            }
        }
    }
