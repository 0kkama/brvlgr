<?php


    namespace App\interfaces;


    use ArrayAccess;
    use Countable;
//    use IteratorAggregate;
    use JsonSerializable;
//    use Serializable;

//    interface ArrayAccessInterface extends ArrayAccess, Countable, IteratorAggregate, Serializable
    interface ArrayAccessInterface extends ArrayAccess, Countable, JsonSerializable
    {

    }
