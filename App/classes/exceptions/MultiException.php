<?php

    namespace App\classes\exceptions;


    use Exception;
    use App\interfaces\ArrayAccessInterface;
    use JsonException;
    use Traversable;

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
//            // TODO: Implement getIterator() method.
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

        public function serialize() : string
        {
            if(!$this->isEmpty()) {
                $res = serialize($this->errors);
            }
            return (empty($res)) ? '' : $res;

//            if(!$this->isEmpty()) {
//                $res = json_encode($this->errors, JSON_THROW_ON_ERROR);
//            }
//            return (empty($res)) ? '' : $res;
        }

        public function unserialize($data) : void
        {
            $this->errors = unserialize($data, ['allowed_classes' => true]);
            //            $this->errors = json_decode($data, true, 16, JSON_THROW_ON_ERROR);
        }

        public function count() : int
        {
            return count($this->errors);
        }

        public function __invoke()
        {
            var_dump($this->errors);
        }

        public function jsonSerialize()
        {
            return $this->errors;
        }
    }
