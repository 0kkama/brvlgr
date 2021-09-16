<?php

    namespace App\traits;

    use App\classes\abstract\exceptions\CustomException;
    use App\classes\exceptions\DbException;
    use App\classes\exceptions\ExceptionWrapper;
    use App\classes\utility\Db;
    use Exception;

    trait FindTrait
    {
        /**
         * @return array
         * @throws ExceptionWrapper
         */
        public static function getAll() : array
        {
            $db = Db::getInstance();
            $sql = 'SELECT * FROM ' . static::TABLE_NAME;
            //            $sql = 'SELECT * FROM ' . 'zorba';
            return $db->queryAll($sql, [], static::class);
        }
        /**
         * Finds needed line in table by given <b>$subject</b> and return it like object of respective class
         * @param string $type type of search subject (id, login, mail etc.)
         * @param string $subject
         * @throws CustomException
         */
        public static function findOneBy(string $type, string $subject) : static
        {
            try {
                $db = Db::getInstance();
                $sql = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE ' . $type.' = :'.$type;
                $result = $db->queryOne($sql, [$type => $subject], static::class);
            } catch (Exception $e) {
                (new DbException($e->getMessage(), 500))->setAlert('Ошибка при запросе к базе данных')->setParam("Ошибка при запросе: `$sql`")->throwIt();
            }
            return $result ?? new static;
        }

        public static function getAllBy(string $type = null, string $subject = null) : array
        {
            $paramsArr = (isset($type, $subject)) ? [$type => $subject]  : [];
            $where = ($paramsArr === []) ? '' : " WHERE $type = :$type";

            $db = Db::getInstance();
            $sql = 'SELECT * FROM ' . static::TABLE_NAME . $where;
            return $db->queryAll($sql, $paramsArr, static::class);
        }
    }
