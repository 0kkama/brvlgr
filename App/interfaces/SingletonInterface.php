<?php


    namespace App\interfaces;


    interface SingletonInterface
    {

        public static function getInstance();
        public function setInstance($params) : void;

    }
