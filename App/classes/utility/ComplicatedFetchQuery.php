<?php


    namespace App\classes\utility;



    use App\classes\Db;
    use App\classes\exceptions\CustomException;
    use App\classes\exceptions\DbException;
    use App\classes\exceptions\ExceptionWrapper;
    use App\interfaces\HasTableInterface;
    use JetBrains\PhpStorm\Pure;
    use PDO;

    class ComplicatedFetchQuery
    {
        protected string $orderly, $column, $subject, $tableName, $sql, $class;
        protected int $start, $end;
        protected bool $desc = false;
        protected Db $db;
        private int $fetchMode;

        public function __construct(HasTableInterface $object, Db $dbHandler, $fetch = PDO::FETCH_CLASS)
        {
            $this->tableName = $object::getTableName();
            $this->class = $object::class;
            $this->db = $dbHandler;
            $this->fetchMode = $fetch;
        }

        //    SELECT * FROM articles // WHERE author = 'Зиппер' // ORDER BY `id` DESC // LIMIT 4,5;
        /**
         * @throws ExceptionWrapper
         */
        public function execute() : ?array
        {
            $this->makeSql();
            $data = (isset($this->column, $this->subject)) ? [$this->column => $this->subject] : [];
            return $this->db->queryAll($this->sql, $data, $this->class, $this->fetchMode);
        }

        protected function makeSql() : void
        {
            $table = $this->tableName;
            $where = $this->getWhere();
            $limit = $this->getLimit();
            $orderly = $this->getOrderly();
            $sort = ($this->desc && !empty($orderly)) ? 'DESC' : '';
            $this->sql = "SELECT * FROM $table $where $orderly $sort $limit";
        }

        //<editor-fold desc="GETTERS">
        public function getOrderly() : string
        {
            return isset($this->orderly) ? "ORDER BY `$this->orderly`" : '';
        }

        public function getLimit() : string
        {
            if (isset($this->start, $this->end) && $this->start < $this->end) {
                $str = "LIMIT $this->start, $this->end";
            } elseif (isset($this->start) && ($this->end === 0)) {
                $str = "LIMIT $this->start";
            } else {
                $str = '';
            }
            return $str;
        }

        public function getWhere() : string
        {
            return isset($this->column, $this->subject) ? ('WHERE ' . $this->column . ' = :' . $this->column) : '';
        }

        /**
         * @return string
         */
        public function getSql() : string
        {
            return $this->sql ?? '';
        }
        //</editor-fold>

        //<editor-fold desc="SETTERS">
        /**
         * @param bool $desc
         */
        public function setDesc(bool $desc) : static
        {
            $this->desc = $desc;
            return $this;
        }

        /**
         * @param string $orderly
         */
        public function setOrderly(string $orderly) : static
        {
            $this->orderly = $orderly;
            return $this;
        }

        public function setLimit(int $limit) : static
        {
            $this->start = $limit;
            $this->end = 0;
            return $this;
        }

        /**
         * @param int $start
         */
        public function setStartAndEnd(int $start, int $end) : static
        {
            $this->start = $start;
            $this->end = $end;
//            if end > start - throw exception?
            return $this;
        }

        /**
         * @param int $end
         */
        public function setEnd(int $end) : static
        {
            $this->end = $end;
            return $this;
        }

        /**
         * @param string $column
         */
        public function setColumn(string $column) : static
        {
            $this->column = $column;
            return $this;
        }

        /**
         * @param string $subject
         */
        public function setSubject(string $subject) : static
        {
            $this->subject = $subject;
            return $this;
        }
        //</editor-fold>
    }
