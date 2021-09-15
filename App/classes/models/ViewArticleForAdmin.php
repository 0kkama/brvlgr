<?php

    namespace App\classes\models;

    use App\classes\abstract\models\AbstractView;

    class ViewArticleForAdmin extends AbstractView
    {
        protected const TABLE_NAME = 'view_artsforadmin';
        protected string $id, $login, $user_id, $title, $text, $category, $moder, $cat_id, $cat_stat, $date;

        public function exist() : bool
        {
            return !empty($this->id) && !empty($this->date);
        }

        //<editor-fold desc="Getters">
        /**
         * @return string
         */
        public function getId(): string
        {
            return $this->id;
        }

        /**
         * @return string
         */
        public function getLogin(): string
        {
            return $this->login;
        }

        /**
         * @return string
         */
        public function getUserId(): string
        {
            return $this->user_id;
        }

        /**
         * @return string
         */
        public function getTitle(): string
        {
            return $this->title;
        }

        /**
         * @return string
         */
        public function getText(): string
        {
            return $this->text;
        }

        /**
         * @return string
         */
        public function getCategory(): string
        {
            return $this->category;
        }

        /**
         * @return string
         */
        public function getModer(): string
        {
            return $this->moder;
        }

        /**
         * @return string
         */
        public function getCatId(): string
        {
            return $this->cat_id;
        }

        /**
         * @return string
         */
        public function getCatStat(): string
        {
            return $this->cat_stat;
        }

        /**
         * @param string $cat_stat
         * @return ViewArticleForAdmin
         */

        /**
         * @return string
         */
        public function getDate(): string
        {
            return $this->date;
        }


        //</editor-fold>
    }
