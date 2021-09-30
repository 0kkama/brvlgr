<?php

    namespace App\classes\utility;

    use App\classes\abstract\exceptions\CustomException;
    use App\classes\exceptions\DbException;
    use App\classes\exceptions\ExceptionWrapper;
    use App\interfaces\SingletonInterface;
    use App\traits\SingletonTrait;
    use PDO;
    use PDOException;
    use PDOStatement;

    /**
     * The singleton class is interface for working with the database through the PDO extension
     */
    final class Db implements SingletonInterface
    {
        private PDO $dbh;
        private static ?Db $instance = null;

        use SingletonTrait;

        private function __construct()
        {
            $this->dbh = $this->newConnection(Config::getInstance());
        }

        public function setInstance($params): void
        {
            $this->dbh = $this->newConnection($params);
        }

        /**
         * Create new connection to data base
         * @param $params
         * @return PDO
         * @throws ExceptionWrapper
         */
        protected function newConnection($params) : PDO
        {
            try {

                $config = $params;
                    $dbConnection = new PDO
                    ('mysql:host=' . $config->getDb('host') . ';dbname=' . $config->getDb('name') . ';charset=' . $config->getDb('char'),
                        $config->getDb('user'), $config->getDb('pass'),
                        [
                            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,
                            PDO::ATTR_PERSISTENT         => true,
                        ]
                    );
            }
            catch (PDOException $ex) {
                (new ExceptionWrapper('Ошибка при запросе к базе данных', 500, $ex, false))->throwIt();
            }
            return $dbConnection;
        }

        /**
         * Checks for errors during the execution of the request
         * @param PDOStatement $query
         * @return bool
         * @throws DbException|CustomException
         */
        protected function checkQueryErr(PDOStatement $query) : bool
        {
            $errInfo = $query->errorInfo();
            if($errInfo[0] !== PDO::ERR_NONE) {
                (new DbException($errInfo[2], 500))->setAlert('Ошибка при запросе к базе данных')->setParam($query->queryString)->throwIt();
            }
            return true;
        }

        /**
         * Method execute inserts $data into query and return true or false
         * depending on the execution succeeded or not
         * @param string $sql
         * @param array $data
         * @return bool
         * @throws DbException|CustomException
         */
        public function execute(string $sql, array $data) : bool
        {
            $query = $this->dbh->prepare($sql);
            $success = $query->execute($data);
            $this->checkQueryErr($query);
            return $success;
        }

        /**
         * The method executes the query, inserts $data into it,
         * returns the array of the query result, or null if execution failed
         * @param string $sql
         * @param array $data
         * @param $class
         * @param int $fetchMode
         * @return array|null
         * @throws ExceptionWrapper
         */
        public function queryAll(string $sql, array $data, $class, int $fetchMode = PDO::FETCH_CLASS) : ?array
        {
            try {
                $query = $this->dbh->prepare($sql);
                $query->execute($data);
                $this->checkQueryErr($query);
            } catch (\Exception $e) {
                (new ExceptionWrapper('Ошибка при осуществлении запроса к базе данных', 500, $e))->throwIt();
            }
                return ($fetchMode === PDO::FETCH_CLASS) ? $query->fetchAll($fetchMode, $class) : $query->fetchAll($fetchMode);
        }

        /**
         * The method executes the request and returns one object of the specified class or null if failed
         * @param string $sql
         * @param array $data
         * @param $class
         * @return object|null
         * @throws DbException|CustomException
         */
        public function queryOne(string $sql, array $data, $class) : ?object
        {
            $query = $this->dbh->prepare($sql);
            $query->setFetchMode(PDO::FETCH_CLASS, $class);
            $query->execute($data);
            $this->checkQueryErr($query);
            $result = $query->fetch();
            return  $result ?: null;
        }

        /**
         * Return last insert id
         * @return string
         */
        public function getLastId() : string
        {
            return $this->dbh->lastInsertId();
        }
    }

