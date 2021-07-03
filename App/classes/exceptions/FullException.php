<?php


    namespace App\classes\exceptions;


    use App\classes\utility\Logger;
    use Exception;
    use JetBrains\PhpStorm\Pure;
    use mysql_xdevapi\SqlStatementResult;
    use Throwable;

    abstract class FullException extends Exception
    {
        /**
         * @var string $alert is outer message for users
         * @var string $param is additional parameter for log message
         * @var string $type is type of Exception for log message
         */
        protected string $alert = '';
        protected string $param = '';
        protected string $type;

        #[Pure] public function __construct($message = "", $code = 0, Throwable $previous = null)
        {
            $this->type = static::class;
            parent::__construct($message, $code, $previous);
        }

        /**
         * Optional message for user
         * @param string $alert
         * @return FullException
         */
        public function setAlert(string $alert) : static
        {
            $this->alert = $alert;
            return $this;
        }

//       public static function create($message = "", $code = 0, Throwable $previous = null) : self
//        {
//            return new static($message, $code, $previous);
//        }

        /**
         * Optional parameters for logs
         * @param string $param
         * @return FullException
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
        public function getFullType() : string
        {
            return $this->type;
        }

        /**
         * @return string
         */
        public function getType() : string
        {
            $pos = strrpos($this->type, '\\');
                return substr($this->type, $pos + 1);
        }

        /**
         * @throws FullException
         */
        public function throwIt() : void
        {
            throw $this;
        }
    }
