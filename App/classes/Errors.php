<?php


    namespace App\classes;


    use JetBrains\PhpStorm\Pure;

    class Errors implements \Countable
    {
        protected array $errors = [];
        protected string $string = '';

        public function count() : int
        {
            return count($this->errors);
        }

        public function __toString() : string
        {
            if ($this->count() !== 0) {
                foreach ($this->errors as $error) {
                    $this->string .= $error;
                }
            }
            return $this->string;
        }

        public function add(string $add) : void {
            $this->errors[] = $add;
        }

        #[Pure] public function __invoke() : bool
        {
            return $this->count() !== 0;
        }

    }
