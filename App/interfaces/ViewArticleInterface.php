<?php

    namespace App\interfaces;

    interface ViewArticleInterface
    {
        public function getId(): string;
        public function getLogin(): string;
        public function getUserId(): string;
        public function getTitle(): string;
        public function getText(): string;
        public function getCategory(): string;
        public function getModer(): string;
        public function getCatId(): string;
        public function getCatStat(): string;
        public function getDate(): string;
        public static function findOneBy(string $type, string $subject) : static;
        public static function getAll() : array;
        public static function getAllBy(string $type = null, string $subject = null) : array;
    }
