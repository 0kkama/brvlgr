<?php

    namespace App\classes;

    use App\classes\exceptions\DbException;
    use App\classes\exceptions\FullException;
    use PDO;
    use PDOException;
    use PDOStatement;
    use App\classes\Config;


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
         */
        public function __construct()
        {
            $this->dbh = $this->newConnection();
        }

        /**
         * @return PDO
         * @throws DbException|exceptions\FullException
         */
        protected function newConnection() : PDO
        {
            try {
                $config = Config::getInstance();
                    $dbConnection = new PDO
                    ('mysql:host=' . $config->getDbHost() . ';dbname=' . $config->getDbName() . ';charset=' . $config->getDbChar(),
                        $config->getDbUser(), $config->getDbPass(),
                        [
                            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,
                            PDO::ATTR_PERSISTENT         => true,
                        ]
                    );
            }
            catch (PDOException $ex) {
                $dbEx = new DbException($ex->getMessage(), 500);
                $dbEx->setAlert('Ошибка в базе данных')->throwIt();
            }
            return $dbConnection;
        }

        /**
         * @param PDOStatement $query
         * @return bool
         * @throws exceptions\DbException|exceptions\FullException
         */
        protected function checkQueryErr(PDOStatement $query) : bool
        {
            $errInfo = $query->errorInfo();
            if($errInfo[0] !== PDO::ERR_NONE) {
                $ex = new DbException($errInfo[2], 500);
                $ex->setAlert('Ошибка при запросе к базе данных')->setParam($query->queryString)->throwIt();
//                trigger_error($errInfo[2], E_USER_ERROR);
            }
            return true;
        }

        /**
         * Метод execute(string $sql) выполняет запрос и возвращает true либо false в зависимости от того,
         * удалось ли выполнение
         * @param string $sql
         * @param array $data
         * @return bool
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
         * @return array|null
         */
        public function queryAll(string $sql, array $data, $class) : ?array
        {
            try {
                $query = $this->dbh->prepare($sql);
                $query->execute($data);
                $this->checkQueryErr($query);
                $result = $query->fetchAll(PDO::FETCH_CLASS, $class);
                return $result ?: null;
            } catch (FullException $fex) {
                throw $fex;
            }
            catch (\Exception $e) {
                $ex = new DbException($e->getMessage(), 500);
                $ex->setAlert('Ошибка при запросе к базе данных')->setParam("{$e->getFile()} {$e->getLine()}")->throwIt();
            }
        }

        /**
         * @param string $sql
         * @param array $data
         * @param $class
         * @return object|null
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

