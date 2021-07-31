<?php

    namespace App\classes\abstract;


    abstract class Model extends AbstractModel
    {

        protected ?string $date = null;

        public function getID() : null|string
        {
            return $this->id;
        }
    }
