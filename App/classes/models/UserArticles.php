<?php

    namespace App\classes\models;

    use App\classes\abstract\models\AbstractModel;


    class UserArticles extends AbstractModel
    {
        protected const TABLE_NAME = 'users_to_articles';
        protected string $user_id = '', $art_id = '';

        public function setUserId(string $user_id) : self
        {
            $this->user_id = $user_id;
            return $this;
        }

        public function setArtId(string $art_id) : self
        {
            $this->art_id = $art_id;
            return $this;
        }

        public function exist(): bool
        {
            return (!empty($this->user_id) && !empty($this->art_id));
        }
    }
