<?php


    namespace App\classes\utility;


    use App\classes\abstract\AbstractModel;
    use App\classes\abstract\Model;
    use App\interfaces\InspectorInterface;
    use App\traits\ArrayAccessTrait;
    use App\traits\IteratorTrait;
    use JetBrains\PhpStorm\Pure;

    class ErrorsContainer implements \Countable, \Iterator, \ArrayAccess, \JsonSerializable
    {
        protected int $key = 0;
//        protected array $data = [];
        protected string $errorsString = '';

        //<editor-fold desc="Iterator interface implementation">
        use IteratorTrait;
        //</editor-fold>

        //<editor-fold desc="ArrayAccess interface implementation">
        use ArrayAccessTrait;
        //</editor-fold>

        public function count() : int
        {
            return count($this->data);
        }

        public function jsonSerialize() : array
        {
            return $this->data;
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

