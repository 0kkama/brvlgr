<?php

    namespace App\classes\models;

    use App\classes\abstract\models\Model;

    class UserSessions extends Model
    {

        protected const TABLE_NAME = 'users_to_sessions';
        protected string $user_id, $sess_id;

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
        public function getSessId(): string
        {
            return $this->sess_id;
        }

        /**
         * @param string $sess_id
         */
        public function setSessId(string $sess_id): self
        {
            $this->sess_id = $sess_id;
            return $this;
        }

        /**
         * @param string $user_id
         */
        public function setUserId(string $user_id): self
        {
            $this->user_id = $user_id;
            return $this;
        }

        public function exist(): bool
        {
            return (!empty($this->user_id) && !empty($this->sess_id));
        }

    }
