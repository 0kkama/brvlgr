<?php

    namespace App\classes\exceptions;


    use App\interfaces\ArrayAccessInterface;
    use Exception;
    use JsonException;

    class MultiException extends CustomException implements ArrayAccessInterface
    {
        protected array $errors = [];

        public function add(Exception $err) : void
        {
            $this->errors[] = $err;
        }

        public function getAll() : array
        {
            return $this->errors;
        }

        public function isEmpty() : bool
        {
            return empty($this->errors);
        }

//        public function getIterator()
//        {
//        }

        public function offsetExists($offset) : bool
        {
            return isset($this->errors[$offset]);
        }

        public function offsetGet($offset)
        {
            return $this->errors[$offset] ?: null;
        }

        public function offsetSet($offset, $value) : void
        {
            is_null($offset) ? $this->errors[] = $value : $this->errors[$offset] = $value;
        }

        public function offsetUnset($offset) : void
        {

            if (is_null($offset)) {
                unset($this->errors[$offset]);
            }
        }

        public function count() : int
        {
            return count($this->errors);
        }

        public function __invoke()
        {
            var_dump($this->errors);
        }

        /**
         * @throws JsonException
         */
        public function jsonSerialize() : string
        {
            return (!empty($this->errors)) ? (json_encode($this->errors, JSON_THROW_ON_ERROR)) : '';
        }

        /**
         * @throws JsonException
         */
        public function jsonUnserialize(string $json) : void
        {
            $this->errors = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        }


    }
