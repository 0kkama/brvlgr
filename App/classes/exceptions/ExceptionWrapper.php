<?php


    namespace App\classes\exceptions;


    use Exception;
    use JetBrains\PhpStorm\Pure;

    class ExceptionWrapper extends Exception
    {

        protected string $myMessage, $type;
        protected int $httpCode;
        protected bool $critical;
        protected array $trace;
        protected Exception $ex;

        /**
         * ExceptionWrapper constructor.
         * @param $message string message using in blind plug for user, not for admin
         * @param $code int using in blind plug for user
         * @param Exception $previous target exception for this wrapper
         * @param false $critical if this status equal true - error is critical and it can be using in message for admin
         */
        #[Pure] public function __construct($message, $code, Exception $previous, bool $critical = false)
        {
            $this->myMessage = $message ?? '';
            $this->httpCode = $code ?? 418;
            $this->critical = $critical;
            $this->type = get_class($previous);

            $this->message = $previous->getMessage();
            $this->code = $previous->getCode();
            $this->file= $previous->getFile();
            $this->line = $previous->getLine();
            $this->code = (int) $previous->getCode();
            $this->trace = $previous->getTrace();

            parent::__construct($this->message, $this->code);
        }

//        public function __get(string $property) : mixed
//        {
//            $method = 'get'.makeFrstLttrUp($property);
//            return (method_exists($this->ex, $method) ? $this->ex->$method : '');
//        }
//
//        public function __set(string $name, $value) : void
//        {
//            // blind plug for __set() method.
//        }
//
//        public function __isset(string $name) : bool
//        {
//        }

        public function getAlert() : string
        {
            return $this->myMessage;
        }

        public function getHttpCode() : int
        {
            return $this->httpCode;
        }

        public function getType() : string
        {
            return $this->type;
        }

        public function isCritical() : bool
        {
            return $this->critical;
        }

        /**
         * @throws ExceptionWrapper
         */
        public function throwIt() : void
        {
            throw $this;
        }
    }
