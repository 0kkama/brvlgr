<?php


    namespace App\interfaces;


    interface Singleton
    {

        public static function getInstance();
        public function setInstance(array $params) : void;

    }
