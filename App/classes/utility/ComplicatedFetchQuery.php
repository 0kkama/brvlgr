<?php


    namespace App\classes\utility;



    use App\classes\exceptions\ExceptionWrapper;
    use App\interfaces\ExistenceInterface;
    use App\interfaces\HasTableInterface;
    use PDO;

    class ComplicatedFetchQuery
    {
        protected string $orderly, $column, $subject, $tableName, $sql, $class, $sign, $and;
        protected int $start, $end;
        protected bool $desc = false;
        protected Db $db;
        private int $fetchMode;

        public function __construct(HasTableInterface|ExistenceInterface $object, Db $dbHandler, $fetch = PDO::FETCH_CLASS)
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
        protected function getOrderly() : string
        {
            return isset($this->orderly) ? "ORDER BY `$this->orderly`" : '';
        }

        protected function getLimit() : string
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

        protected function getWhere() : string
        {
            return (!empty($this->column) && !empty($this->subject)) ? ("WHERE `{$this->column}` $this->sign :{$this->column}") : '';
        }

        /**
         * @return string
         */
        public function getSql() : string
        {
            $this->makeSql();
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
//            todo if end > start - throw exception?
            return $this;
        }

        /**
         * @param string $column - name of column for WHERE filter
         * @param string $subject - value of column for WHERE filter
         */
        public function setWhere(string $column, string $subject, string $sign = '=') : self
        {
            $this->column = $column;
            $this->subject = $subject;
            $this->sign = $sign;
            return $this;
        }

//        public function setAnd(string $column, string $subject, string $sign = '=') : self
//        {
//            $this->and = " AND `{$column}` $sign :{$column}";
//        }

//        public function setIN(string $column,array $sequence) : self
//        {
//            $this->column = $column;
//            $this->subject = '(' . implode(',', $sequence) . ')';
//            return $this;
//        }
        //</editor-fold>
    }
