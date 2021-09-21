<?php

    namespace App\classes\models;

    use App\classes\abstract\models\Model;

    class AdminMenu extends Model
    {
        protected string $title = '', $url = '';
        protected const TABLE_NAME = 'admin_menu';

        public function exist() : bool
        {
            return (!empty($this->id) && !empty($this->title));
        }

        public function __toString() : string
        {
            return "<li><a href={$this->getUrl()}>{$this->getTitle()}</a></li>";
        }

        /**
         * @param string $url
         * @return $this
         */
        public function setUrl(string $url) : self
        {
            $this->url = $url;
            return $this;
        }

        /**
         * @param string $title
         * @return $this
         */
        public function setTitle(string $title) : self
        {
            $this->title = $title;
            return $this;
        }

        /**
         * @return string
         */
        public function getTitle() : string
        {
            return $this->title;
        }

        /**
         * @return string
         */
        public function getUrl() : string
        {
            return $this->url;
        }
    }
