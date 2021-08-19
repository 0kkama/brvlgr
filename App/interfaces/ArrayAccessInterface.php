<?php


    namespace App\interfaces;


    use ArrayAccess;
    use Countable;
    use Iterator;
    use JsonSerializable;

//    interface ArrayAccessInterface extends ArrayAccess, Countable, IteratorAggregate, Serializable

    interface ArrayAccessInterface extends ArrayAccess, Countable, Iterator, JsonSerializable
    {

    }
