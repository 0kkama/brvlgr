<?php

    namespace App\classes\utility\containers;

    use App\classes\abstract\utility\AbstractContainer;
    use App\classes\models\Navigation;

    class NavigationBar extends AbstractContainer
    {
        public function add(Navigation $row) : void {
            $this->data[] = $row;
        }

        public function addArray(array $rows) : void
        {
            foreach ($rows as $row) {
                if (!empty($row)) {
                    $this->data[] = $row;
                }
            }
        }

        public function __invoke($place) : string
        {
            $string = '';
            foreach ($this->data as $key => $row) {
                $string .= $row($place) . ' ';
            }
            return $string;
        }
    }
