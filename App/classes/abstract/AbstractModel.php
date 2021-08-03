<?php

    namespace App\classes\abstract;

    use App\classes\Db;
    use App\classes\exceptions\CustomException;
    use App\classes\exceptions\DbException;
    use App\classes\exceptions\ExceptionWrapper;
    use App\classes\utility\ErrorsContainer;
    use App\classes\utility\ErrorsInspector;
    use App\interfaces\HasIdInterface;
    use Exception;
    use JetBrains\PhpStorm\Pure;
    use PDO;

    /**
     * Class AbstractModel has following methods : <ul>
     * - <b>findById</b> - return null|object</li>
     * - <b>getAll</b> - return array contain objects of respective class</li>
     * - <b>insert</b>
     * - <b>update</b>
     * - <b>delete</b>
     * - <b>save</b>
     * - <b>makeSQL</b>
     * - <b>checkFields</b>
     * - <b>getTableName</b>
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
    protected ErrorsContainer $errors;
    protected ErrorsInspector $inspector;
    protected static array $errorsList;
    protected static array $checkList;
    protected array $meta = ['table' => null, 'cols' => null, 'data' => null, 'separator' => null];

//    TODO перенести метода exist в абстракцию
//    TODO вынести проверку заполненности полей в отдельный класс?
        public function __construct(ErrorsInspector $inspector = null)
        {
            $this->errors = new ErrorsContainer();
//            $this->inspector = new ErrorsInspector();
            $this->inspector = $inspector ?: new ErrorsInspector();
            $this->inspector->setObject($this);
            $this->inspector->setContainer($this->errors);
        }

        /**
         * Finds needed line in table by given <b>$subject</b> and return it like object of respective class
         * @param string $type type of search subject (id, login, mail etc.)
         * @param string $subject
         * @return AbstractModel
         */
        public static function findOneBy(string $type, string $subject) : static
        {
            try {
                $db = new Db();
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
            $db = new Db();
            $sql = 'SELECT * FROM ' . static::TABLE_NAME;
//            $sql = 'SELECT * FROM ' . 'zorba';
            return $db->queryAll($sql, [], static::class);
        }

        /**
         * @throws DbException|CustomException
         * @throws ExceptionWrapper
         */
        public static function getTotalQuantity()
        {
            $db = new Db();
            $sql = 'SELECT COUNT(*) AS ' . static::TABLE_NAME . ' FROM ' . static::TABLE_NAME;
            return $db->queryAll($sql, [], static::class, PDO::FETCH_ASSOC);
        }

        /**
         * метод добавлет новую запись в БД, после чего возвращает <b>$this</b> или <b>null</b>
         * @return AbstractModel
         */
        protected function insert() : static
        {
            // делаем строку подобную :title, :text, :author, :category
            $values = implode($this->meta['separator'], $this->meta['cols']);
            // делаем строку подобную title, text, author, category
            $insertions = implode($this->meta['separator'], array_flip($this->meta['cols']));
            // создаем шаблон запроса вида INSERT INTO news (title,text,author,category) VALUES (:title,:text,:author,:category)
            $sql = "INSERT INTO {$this->meta['table']} ($insertions) VALUES ($values)";

            $db = new Db();
            $db->execute($sql, $this->meta['data']);
            $this->id = $db->getLastId();
            unset($this->meta);
            return $this;
        }

        /**
         * Обновляет уже существующую запись, которая ранее была получена из базы данных по id
         * @return AbstractModel
         */
        protected function update() : static
        {
            //   TODO Как реализовать обновление только того поля, которое было изменено?
            $set = [];
            foreach ($this->meta['cols'] as $index => $value) {
                $set[] = "$index = $value";
            }

            $this->meta['data'][":id"] = $this->id;
            $set = implode($this->meta['separator'], $set);
            // шаблон подобный UPDATE news SET title = :title, text = :text, author = :author WHERE id = :id
            $sql = "UPDATE {$this->meta['table']} SET $set WHERE id = :id";

            $db = new Db();
            $db->execute($sql, $this->meta['data']);
            unset($this->meta);
            return $this;
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

                $db = new Db();
                return $db->execute($sql, $data);
            }
            return false;
        }

        /**
         * Defines whether a record is old or new. If the record is new,
         * then the <b>insert</b> method will be called, otherwise the <b>update</b> method.
         * @return AbstractModel
         */
        public function save() : static
        {
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
        protected function makeSql() : void
        {
            // удаляем значения id, date итд не являющиеся строками и генерируемые БД автоматически или выполняющие служебные цели
            $fields = array_filter(get_object_vars($this), static function ($var) { return is_string($var); });
            $this->meta['cols'] = $this->meta['data'] = [];

            foreach ($fields as $index => $value) {
                $this->meta['cols'][$index] = ":$index"; // массив вида 'index' => ':index'
                $this->meta['data'][":$index"] = $value; // данные для внедрения вида ':user' => 'Ahmed'
            }

            $this->meta['separator'] = ', '; // разделитель, используемый в текущем запросе
            $this->meta['table'] = static::TABLE_NAME; // имя таблицы в БД
        }

        public function checkData() : static
        {
            $this->makeSql();
            $this->inspector->checkFormFields();
            if ($this->errors->notEmpty()) {
                return $this;
            }
            if (!empty(static::$checkList)) {
                $this->inspector->validateData(static::$checkList);
            }
                return $this;
        }

        /**
         * Возвращает <b>string</b> с именем текущей таблицы
         * @return string
         */
        public static function getTableName() : string
        {
            return static::TABLE_NAME;
        }

        public function getID() : null|string
        {
            return $this->id;
        }

        public function getErrorsList() : array
        {
            return static::$errorsList;
        }

        public function getMetaData() : array
        {
            return $this->meta['data'];
        }

        abstract public function exist() : bool;
    }
