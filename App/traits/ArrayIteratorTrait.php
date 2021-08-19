<?php

    namespace App\traits;

    use ArrayIterator;

    trait ArrayIteratorTrait
    {
        public function getIterator() : ArrayIterator
        {
            return new ArrayIterator($this->data);
        }
    }
