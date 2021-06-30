<?php


    namespace App\interfaces;


    use ArrayAccess;
    use Countable;
    use IteratorAggregate;
    use Serializable;

    interface ArrayAccessInterface extends ArrayAccess, Countable, IteratorAggregate, Serializable
    {

    }
