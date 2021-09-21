<?php

    namespace App\classes\models;

    use App\classes\abstract\models\Model;
    use phpDocumentor\Reflection\Types\This;

    class ArticleCategories extends Model
    {
        protected const TABLE_NAME = 'articles_to_categories';
        protected string $art_id, $cat_id;

        /**
         * @param string $art_id
         */
        public function setArtId(string $art_id) : self
        {
            $this->art_id = $art_id;
            return $this;
        }

        /**
         * @param string $cat_id
         */
        public function setCatId(string $cat_id) : self
        {
            $this->cat_id = $cat_id;
            return $this;
        }

        /**
         * @return string
         */
        public function getCatId(): string
        {
            return $this->cat_id;
        }

        public function exist(): bool
        {
            return (!empty($this->art_id) && !empty($this->cat_id));
        }
    }
