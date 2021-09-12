<?php

    namespace App\classes\utility\containers;

    use App\classes\abstract\utility\AbstractContainer;
    use App\classes\models\AdminMenu;

    class AdminMenuContainer extends AbstractContainer
    {
        public function add(AdminMenu $row) : void {
            $this->data[] = $row;
        }

        public function __toString(): string
        {
            return '<ul>' . implode('', $this->data) . '</ul>';
        }
    }
