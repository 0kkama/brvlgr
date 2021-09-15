<?php

    namespace App\classes\utility\containers;

    use App\classes\abstract\utility\AbstractContainer;
    use App\classes\models\Categories;

    class CategoriesList extends AbstractContainer
    {
        protected int|string $index;
        public function add(Categories $row) : void
        {
            $this->data[] = $row;
        }

        public function __toString() : string
        {
            return '<ul>' . implode('', $this->data) . '</ul>';
        }

        public function __invoke(string $cat_mark) : string
        {
            $string = '';
            foreach ($this->data as $index => $datum) {
                $string .= $datum($cat_mark);
            }
            return $string;
        }

//        public function getCategoryBy(string $type, $value) : ?Categories
//        {
//            if ($this->checkCategoryInBy($type, $value)) {
//                $index = null;
//                foreach ($this->data as $key => $value) {
//                    if ($value->getUrl() === $url) {
//                        $index = $key;
//                    }
//                }
//                return ($this->data[$index]) ?: null;
//            }
//
//        }

        public function checkCategoryInBy(string $type , $value) : bool
        {
            $getter = 'get' . ucfirst($type);
            if (method_exists(new Categories(), $getter)) {
                foreach ($this->data as $index => $category) {
                    if ($value === $category->$getter()) {
                        $this->index = $index;
                        return true;
                    }
                }
            }
            return false;
        }

        public function getCategoryByIndex() : ?Categories
        {
            return (isset($this->index)) ? $this->data[$this->index] : null;
        }
    }
