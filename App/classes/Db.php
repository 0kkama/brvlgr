<?php

    namespace App\classes;

    use App\classes\exceptions\DbException;
    use App\classes\exceptions\ExceptionWrapper;
    use App\classes\exceptions\CustomException;
    use PDO;
    use PDOException;
    use PDOStatement;
    use App\classes\Config;

//TODO разобраться с периодически возникающей ошибкой
// 2 - Packets out of order. Expected 1 received 0. Packet size=145 in App/classes/Db.php line:45
    /**

     */
    class Db
    {
        /**
         * @var PDO
         */
        private PDO $dbh;

        /**
         * Db constructor.
         * @throws ExceptionWrapper
         */
        public function __construct()
        {
            $this->dbh = $this->newConnection();
        }

        /**
         * @return PDO
         * @throws ExceptionWrapper
         */
        protected function newConnection() : PDO
        {
            try {
                $config = Config::getInstance();
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
//                (new DbException($ex->getMessage(), 500))->setAlert('Ошибка при запросе к базе данных')->throwIt();
            }
            return $dbConnection;
        }

        /**
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
         * Метод execute(string $sql) выполняет запрос и возвращает true либо false в зависимости от того,
         * удалось ли выполнение
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
         * Метод query(string $sql, array $data) выполняет запрос, подставляет в него данные $data,
         * возвращает данные результата запроса либо false, если выполнение не удалось
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
                return ($fetchMode === 8) ? $query->fetchAll($fetchMode, $class) : $query->fetchAll($fetchMode);
        }

        /**
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
         * @return string
         */
        public function getLastId() : string
        {
            return $this->dbh->lastInsertId();
        }

    }

