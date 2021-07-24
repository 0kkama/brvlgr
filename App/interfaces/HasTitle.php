<?php


    namespace App\interfaces;


    interface HasTitle
    {
        public const TITLE = 'title';

        public function getTitle();
    }
