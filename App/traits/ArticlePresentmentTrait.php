<?php

    namespace App\traits;

    trait ArticlePresentmentTrait
    {
        public function  getFormattedContent() : string
        {
            $string = $this->text;
            $paragraphs = [];
            $arr = explode(PHP_EOL, $string);

            foreach ($arr as $row) {
                $paragraphs[] = '<p>' . $row . '</p>';
            }

            return implode(PHP_EOL, $paragraphs);
        }

        public function getBriefContent($length = 150) : string
        {
            return (mb_substr($this->text, 0, $length) . '...') ?? '';
        }

        public function __toString() : string
        {
            return 'Дата: ' . $this->date . '<br>' . 'Автор: ' . $this->login . '<br>' . 'Категория: '. $this->category . '<br>';
        }
    }
