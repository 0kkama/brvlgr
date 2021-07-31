<?php


    namespace App\interfaces;


    interface HasTitleInterface
    {
        public const TITLE = 'title';

        public function getTitle();
    }
