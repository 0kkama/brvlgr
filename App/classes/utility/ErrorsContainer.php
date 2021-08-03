<?php


    namespace App\classes\utility;


    use App\classes\abstract\AbstractModel;
    use App\classes\abstract\Model;
    use App\interfaces\InspectorInterface;
    use Countable;
    use JetBrains\PhpStorm\Pure;

    class ErrorsContainer implements Countable
    {
        protected array $errors = [];
        protected string $errorString = '';

        public function count() : int
        {
            return count($this->errors);
        }

        /**
         * Return all contained errors as one formatted string
         * @return string
         */
        public function __toString() : string
        {
            if ($this->count() !== 0) {
                foreach ($this->errors as $error) {
                    $this->errorString .= $error . ' <br>';
                }
            }
            return $this->errorString;
        }

        public function add(string $error) : void {
            $this->errors[] = $error;
        }

        public function reset() : void {
            $this->errors = [];
        }

        public function getErrors() : array
        {
            return $this->errors;
        }

        #[Pure] public function getFirst() : string {
            if ($this->count() !== 0) {
                return $this->errors[0];
            }
            return '';
        }

        #[Pure] public function notEmpty() : bool
        {
            return $this->count() !== 0;
        }
    }

