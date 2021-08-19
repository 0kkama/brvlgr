<?php


    namespace App\classes\utility;


    use App\traits\ArrayAccessTrait;
    use App\traits\ArrayIteratorTrait;
    use App\traits\CountableTrait;
    use App\traits\JsonSeializableTrait;
    use ArrayAccess;
    use ArrayIterator;
    use Countable;
    use IteratorAggregate;
    use JetBrains\PhpStorm\Pure;
    use JsonSerializable;

    class ErrorsContainer implements ArrayAccess, Countable, JsonSerializable, IteratorAggregate
    {
        protected int $key = 0;
        protected string $errorsString = '';
//        protected array $data = [];

        //<editor-fold desc="Interfaces implementation">
//        use IteratorTrait;
        use ArrayAccessTrait;
        use CountableTrait;
        use JsonSeializableTrait;
        use ArrayIteratorTrait;
        //</editor-fold>

        public function getIterator() : ArrayIterator
        {
            return new ArrayIterator($this->data[]);
        }

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

        public function addArray(array $errors) : void
        {
            foreach ($errors as $error) {
                if (!empty($error)) {
                    $this->data[] = $error;
                }
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

