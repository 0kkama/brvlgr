<?php

    namespace App\traits;

    trait ArrayAccessTrait
    {
        protected array $data = [];
        /**
         * @param mixed $offset
         * @param mixed $value
         */
        public function offsetSet($offset, $value) : void
        {
            if (!empty($offset) && !empty($value)) {
                $this->data[$offset] = $value;
            }
        }
        /**
         * @param mixed $offset
         * @return bool
         */
        public function offsetExists($offset) : bool
        {
            return isset($this->data[$offset]);
        }
        /**
         * @param mixed $offset
         */
        public function offsetUnset($offset) : void
        {
            if ($this->offsetExists($offset)) {
                unset($this->data[$offset]);
            }
        }
        /**
         * @param mixed $offset
         * @return string|null
         */
        public function offsetGet($offset) : ?string
        {
            return $this->container[$offset] ?? null;
        }
    }
