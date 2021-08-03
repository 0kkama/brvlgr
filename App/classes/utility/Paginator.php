<?php


    namespace App\classes\utility;


    use App\interfaces\PaginatedInterface;

    class Paginator
    {
        protected PaginatedInterface $subject;
        protected int $itemQuantity = 0;
        protected int $itemPerPage = 0;
        protected int $currentPage = 0;
        protected int $pageQuantity = 0;

        public function __construct(PaginatedInterface $subject)
        {

        }

        public function aaa()
        {

        }

        public function bbb()
        {

        }

    }
