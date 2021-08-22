<?php


    namespace App\classes\abstract\exceptions;


    use Exception;
    use JetBrains\PhpStorm\Pure;
    use Throwable;

    abstract class CustomException extends Exception
    {
        /**
         * @var string $alert is outer message for users
         * @var string $param is additional parameter for log message
         * @var string $type is type of Exception for log message
         */

        protected string $alert = '', $param = '', $type;
        public int $httpCode;
        protected bool $critical;

        #[Pure] public function __construct($message = "", $code = 0, $critical = false, Throwable $previous = null)
        {
            $this->type = static::class;
            $this->critical = $critical;
            $this->httpCode = $code;
            parent::__construct($message, $code, $previous);
        }

        /**
         * Optional message for user
         * @param string $alert
         * @return CustomException
         */
        public function setAlert(string $alert) : static
        {
            $this->alert = $alert;
            return $this;
        }

        public function getHttpCode() : int
        {
            return $this->httpCode ?? 418;
        }

        /**
         * Optional parameters for logs
         * @param string $param
         * @return CustomException
         */
        public function setParam(string $param) : static
        {
            $this->param = $param;
            return $this;
        }

        /**
         * @return string
         */
        public function getAlert() : string
        {
            return $this->alert;
        }

        /**
         * @return string
         */
        public function getParam() : string
        {
            return $this->param;
        }

        /**
         * @return string
         */
        public function getType() : string
        {
            $pos = strrpos($this->type, '\\');
                return substr($this->type, $pos + 1);
        }

        public function isCritical() : bool
        {
            return $this->critical;
        }

        /**
         * @throws CustomException
         */
        public function throwIt() : void
        {
            throw $this;
        }
    }
