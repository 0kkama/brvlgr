<?php

    namespace App\classes\utility\containers;

    use App\classes\abstract\utility\AbstractContainer;
    use App\classes\models\Categories;

    class CategoriesList extends AbstractContainer
    {
        public function add(Categories $row) : void
        {
            $this->data[] = $row;
        }

        public function __toString() : string
        {
            return implode('', $this->data);
        }

        public function __invoke(string $cat_mark) : string
        {
            $string = '';
            foreach ($this->data as $index => $datum) {
                $string .= $datum($cat_mark);
            }
            return $string;
        }


    }
