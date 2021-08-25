<?php

    namespace App\classes\abstract\models;

    use App\classes\utility\Db;
    use App\classes\abstract\exceptions\CustomException;
    use App\classes\exceptions\DbException;
    use App\classes\exceptions\ExceptionWrapper;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\FormsWithData;
    use App\interfaces\HasIdInterface;
    use Exception;
    use PDO;

    /**
     * Class AbstractModel has following main methods : <ul>
     * - <b>findById</b> - return null|object</li>
     * - <b>getAll</b> - return array contain objects of respective class</li>
     * - <b>insert</b> - insert a new record in the table
     * - <b>update</b> - update already existed record in the table
     * - <b>delete</b> - delete record from table
     * - <b>save</b> - switcher for <b>insert</b> and <b>update</b> methods
     * - <b>makeSQL</b> - service function for creating and preparing SQL query
     * @package App\classes
     */
    abstract class AbstractModel implements HasIdInterface
    {
    /**
     *@const const TABLE_NAME dynamically changing in inheriting classes
     * @var ErrorsContainer $errors
     * @var array $mata collection of data for forming sql queries
     */
    protected const TABLE_NAME = 'abstract';
    protected ?string $id = null;
    protected array $meta = ['table' => null, 'cols' => null, 'data' => null, 'separator' => null];

        /**
         * Finds needed line in table by given <b>$subject</b> and return it like object of respective class
         * @param string $type type of search subject (id, login, mail etc.)
         * @param string $subject
         * @return AbstractModel
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

        /**
         * @return array
         * @throws ExceptionWrapper
         */
        public static function getAll() : array
        {
            $db = Db::getInstance();
            $sql = 'SELECT * FROM ' . static::TABLE_NAME;
            return $db->queryAll($sql, [], static::class);
        }

        /**
         * @throws DbException|CustomException
         * @throws ExceptionWrapper
         */
        public static function getTotalQuantity()
        {
            $db = Db::getInstance();
            $sql = 'SELECT COUNT(*) AS ' . static::TABLE_NAME . ' FROM ' . static::TABLE_NAME;
            return $db->queryAll($sql, [], static::class, PDO::FETCH_ASSOC);
        }

        /**
         * метод добавлет новую запись в БД, после чего возвращает <b>$this</b> или <b>null</b>
         * @return AbstractModel
         */
        private function insert() : bool
        {
            // делаем строку подобную :title, :text, :author, :category
            $values = implode($this->meta['separator'], $this->meta['cols']);
            // делаем строку подобную title, text, author, category
            $insertions = implode($this->meta['separator'], array_flip($this->meta['cols']));
            // создаем шаблон запроса вида INSERT INTO news (title,text,author,category) VALUES (:title,:text,:author,:category)
            $sql = "INSERT INTO {$this->meta['table']} ($insertions) VALUES ($values)";

            $db = Db::getInstance();
            $db->execute($sql, $this->meta['data']);
            $this->id = $db->getLastId();
            unset($this->meta);
            return true;
        }

        /**
         * Обновляет уже существующую запись, которая ранее была получена из базы данных по id
         * @return AbstractModel
         */
        private function update() : bool
        {
            $set = [];
            foreach ($this->meta['cols'] as $index => $value) {
                $set[] = "$index = $value";
            }

            $this->meta['data'][":id"] = $this->id;
            $set = implode($this->meta['separator'], $set);
            // шаблон подобный UPDATE news SET title = :title, text = :text, author = :author WHERE id = :id
            $sql = "UPDATE {$this->meta['table']} SET $set WHERE id = :id";

            $db = Db::getInstance();
            $db->execute($sql, $this->meta['data']);
            unset($this->meta);
//            return $this;
            return true;
        }

        /**
         * Удаляет запись из БД
         * @return bool
         */
        public function delete() : bool
        {
            if (isset($this->id)) {
                $table = static::TABLE_NAME;
                $sql = "DELETE FROM $table WHERE id = :id";
                $data[':id'] = $this->id;

                $db = Db::getInstance();
                return $db->execute($sql, $data);
            }
            return false;
        }

        /**
         * Defines whether a record is old or new. If the record is new,
         * then the <b>insert</b> method will be called, otherwise the <b>update</b> method.
         * @return boolean
         */
        public function save() : bool
        {
            $this->makeSql();
            if (isset($this->id)) {
                return $this->update();
            }
            return $this->insert();
        }

        /**
         * Внутренний метод, формирующий данные для последующей подстановки в SQL запрос.
         * Предварительно удаляет данные полей, не являющихся типом string и генерируемых БД автоматически.
         * В конце работы проверяет заполненность всех полей
         * Возвращает ассоциативный массив с данными: <ul>
         * <li><b>table</b> - имя таблицы;</li>
         * <li><b>cols</b> - массив вида 'index' => ':index' для подготовленных запросов;</li>
         * <li><b>data</b> - массив данных для подстановки (':user' => 'Ahmed');</li>
         * <li><b>separator</b> - символ-разделитель, использующийся в запросе;</li></ul>
         * @return void
         */
        private function makeSql() : void
        {
            // удаляем значения id, date итд не являющиеся строками и генерируемые БД автоматически или выполняющие служебные цели
            $fields = $this->getFormFields();
            $this->meta['cols'] = $this->meta['data'] = [];

            foreach ($fields as $index => $value) {
                $this->meta['cols'][$index] = ":$index"; // массив вида 'index' => ':index'
                $this->meta['data'][":$index"] = $value; // данные для внедрения вида ':user' => 'Ahmed'
            }

            $this->meta['separator'] = ', '; // разделитель, используемый в текущем запросе
            $this->meta['table'] = static::TABLE_NAME; // имя таблицы в БД
        }

        //<editor-fold desc="getters =======================">
        /**
         * Возвращает <b>string</b> с именем текущей таблицы
         * @return string
         */

        public function setFields(FormsWithData $forms) : static
        {
            foreach ($forms as $index => $form) {
                $method = 'set' . ucfirst($index);
                if (method_exists($this, $method)) {
                    $this->$method($form);
                }
            }
            return $this;
        }

        public static function getTableName() : string
        {
            return static::TABLE_NAME;
        }

        public function getId() : null|string
        {
            return $this->id;
        }

        public function getFormFields() : array
        {
            return array_filter(get_object_vars($this), static function ($var) {
                return is_string($var);
            });
        }

        public function getFieldsName() : array
        {
            return array_keys(array_filter(get_object_vars($this), static function ($var) {
                return is_string($var);
            }));
        }

        //</editor-fold>

        abstract public function exist() : bool;
    }
