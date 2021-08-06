<?php

    namespace App\traits;

    trait IteratorTrait
    {
        /**
         * @return mixed
         */
        public function current() : mixed
        {
            return $this->data[$this->key];
        }

        /**
         * @return void
         */
        public function next() : void
        {
            ++$this->key;
        }

        /**
         * @return int
         */
        public function key() : int
        {
            return $this->key;
        }

        /**
         * @return bool
         */
        public function valid() : bool
        {
            return isset($this->data[$this->key]);
        }

        /**
         * @return void
         */
        public function rewind() : void
        {
            $this->key = 0;
        }
    }
