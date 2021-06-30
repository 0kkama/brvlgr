<?php


    namespace App\classes\utility;
    use App\classes\exceptions\FullException;
    use Exception;
    use JetBrains\PhpStorm\Pure;


    class Logger
    {
        protected int|string $number;
        protected string $file, $line, $date, $time, $message, $log = '', $type = '', $alert = '', $param = '';
        protected static string $path = __DIR__ . '/../../../logs/errors';

        #[Pure] protected function __construct(Exception $ex)
        {
            $this->number = $ex->getCode();
            $this->file = $ex->getFile();
            $this->line = $ex->getLine();
            $this->message = $ex->getMessage();
            $this->type = get_class($ex);
            $this->date = date('Y-m-d');
            $this->time = date('H:i:s');

            if ($ex instanceof FullException) {
                $this->alert = $ex->getAlert();
                $this->param = $ex->getParam();
                $this->type = $ex->getType();
            }
        }

        #[Pure] public static function create(Exception $ex) : Logger
        {
            return new self($ex);
        }

        public function write() : Logger
        {
            $logFile = static::$path . '/err_' . $this->date . '.log';

            $this->log = "$this->time - $this->number - $this->type $this->message $this->param in $this->file line:$this->line";
            error_log("$this->log\n", 3, $logFile);
            return $this;
        }

        //        protected function getClassName(FullException $ex) : string
        //        {
        //            $longName = $ex->;
        //            $pos = strrpos($longName, '\\');
        //            if (is_numeric($pos)) {
        //                return substr($longName, $pos + 1);
        //            }
        //            return 'Unidentified class';
        //        }
    }
