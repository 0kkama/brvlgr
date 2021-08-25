<?php


    namespace App\classes\utility\loggers;


    use App\classes\abstract\exceptions\CustomException;
    use App\classes\exceptions\ExceptionWrapper as Wrapper;
    use App\classes\utility\EmailSender;
    use App\interfaces\CanSendMessageInterface;
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
        protected CanSendMessageInterface $sender;
        protected static string $path = __DIR__ . '/../../../../logs/errors/';

        public function __construct(Exception $ex, CanSendMessageInterface $sender)
        {
            $this->ex = $ex;
            $this->date = date('Y-m-d');
            $this->time = date('H:i:s');
            $this->logFile = static::$path . $this->date . '.log';
            $this->sender = $sender;
        }

        public function __invoke() : void
        {
            if ($this->ex instanceof Wrapper) {
                $this->logMessage = "$this->time - {$this->ex->getCode()} - {$this->ex->getType()} {$this->ex->getMessage()} in {$this->ex->getFile()} line:{$this->ex->getLine()}";
                $this->ifIsCritical();
            } elseif ($this->ex instanceof CustomException) {
                $this->logMessage = "$this->time - {$this->ex->getCode()} - {$this->ex->getType()} {$this->ex->getMessage()} in {$this->ex->getFile()} line:{$this->ex->getLine()} {$this->ex->getParam()}";
                $this->ifIsCritical();
            } else {
                $this->logMessage = "$this->time - {$this->ex->getCode()} - {$this->ex->getMessage()} in {$this->ex->getFile()} line:{$this->ex->getLine()}";
            }
                error_log("$this->logMessage\n", 3, $this->logFile);
        }

//        TODO доделать работу метода ifIsCritical!
        public function ifIsCritical() : void
        {
            if ($this->ex->isCritical()) {
                $subject = $this->date."(Сообщение: {$this->ex->getAlert()} код: {$this->ex->getНttpCode()})";
                EmailSender::sendMessage($subject, $this->logMessage);
            }
        }
    }
