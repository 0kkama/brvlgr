<?php

    namespace App\classes\abstract\utility;

    use App\traits\ArrayAccessTrait;
    use App\traits\ArrayIteratorTrait;
    use App\traits\CountableTrait;
    use App\traits\JsonSeializableTrait;
    use ArrayAccess;
    use ArrayIterator;
    use Countable;
    use IteratorAggregate;
    use JsonSerializable;

    abstract class AbstractContainer implements ArrayAccess, Countable, JsonSerializable, IteratorAggregate
    {
        protected int $key = 0;

        use ArrayAccessTrait, CountableTrait, JsonSeializableTrait, ArrayIteratorTrait /*,IteratorTrait*/;

        public function getIterator() : ArrayIterator
        {
            return new ArrayIterator($this->data);
        }
//        abstract public function add(object $row) : void;

        public function addArray(array $elements) : void
        {
            foreach ($elements as $element) {
                $this->add($element);
            }
        }


    }
