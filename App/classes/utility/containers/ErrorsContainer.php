<?php


    namespace App\classes\utility\containers;


    use App\classes\abstract\utility\AbstractContainer;
    use JetBrains\PhpStorm\Pure;

    class ErrorsContainer extends AbstractContainer
    {
        protected string $errorsString = '';

        /**
         * Return all contained errors as one formatted string
         * @return string
         */
        public function __toString() : string
        {
            if ($this->count() !== 0) {
                foreach ($this->data as $error) {
                    $this->errorsString .= $error . ' <br>';
                }
            }
            return $this->errorsString;
        }

        public function add(string $error) : void {
            if(!empty($error)) {
                $this->data[] = $error;
            }
        }

        public function reset() : void {
            $this->data = [];
        }

        public function getData() : array
        {
            return $this->data;
        }

        #[Pure] public function getFirst() : string {
            if ($this->count() !== 0) {
                return $this->data[0];
            }
            return '';
        }
        #[Pure] public function notEmpty() : bool
        {
            return $this->count() !== 0;
        }

        #[Pure] public function isEmpty() : bool
        {
            return $this->count() === 0;
        }
    }

