<?php

    namespace App\classes\exceptions;


    use Exception;
    use App\interfaces\ArrayAccessInterface;
    use Traversable;

    class MultiException extends FullException implements ArrayAccessInterface
    {
        protected array $errors = [];

        public function add(Exception $err) : void
        {
            $this->errors[] = $err;
        }

        public function all() : array
        {
            return $this->errors;
        }

        public function empty() : bool
        {
            return empty($this->errors);
        }

        public function getIterator()
        {
            // TODO: Implement getIterator() method.
        }

        public function offsetExists($offset)
        {
            // TODO: Implement offsetExists() method.
        }

        public function offsetGet($offset)
        {
            // TODO: Implement offsetGet() method.
        }

        public function offsetSet($offset, $value)
        {
            // TODO: Implement offsetSet() method.
        }

        public function offsetUnset($offset)
        {
            // TODO: Implement offsetUnset() method.
        }

        public function serialize()
        {
            // TODO: Implement serialize() method.
        }

        public function unserialize($data)
        {
            // TODO: Implement unserialize() method.
        }

        public function count()
        {
            // TODO: Implement count() method.
        }
    }
