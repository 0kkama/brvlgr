<?php

    namespace App\classes\abstract;
//
//    use classes\models\Article;
//    use App\interfaces\Singleton;
    use App\classes\Db;
    use App\classes\UsersErrors;
use App\interfaces\HasId;
use App\interfaces\Shitty;

/**
 * Class Govno has following methods : <ul>
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
    abstract class Govno implements Shitty, HasId
    {

    /**
     *@const const TABLE_NAME dynamically changing in inheriting classes
     * @var UsersErrors $errors
     * @var null $table
     * @var null $cols
     * @var null $data
     * @var null $separator
     */
    protected const TABLE_NAME = 'Govno';
    protected UsersErrors $errors;
    protected array $replacements;
    protected array $meta = ['table' => null, 'cols' => null, 'data' => null, 'separator' => null];

        /**
         * Finds needed line in table by given <b>$id</b> and return it like object of respective class
         * @param string $id
         * @return Govno
         */
        public static function findById(string $id) : static
        {
            $db = new Db();
            $sql = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE id = :id';
            $result = $db->queryOne($sql, ['id' => $id], static::class);
            return $result ?? new static;
        }

        /**
         * @return array
         */
        public static function getAll() : array
        {
            $db = new Db();
            $sql = 'SELECT * FROM ' . static::TABLE_NAME;
            return $db->queryAll($sql, [], static::class);
        }

        /**
         * метод добавлет новую запись в БД, после чего возвращает  <b>$this</b> или <b>null</b>
         * @return Govno
         */
        protected function insert() : static
        {
//            extract($this->makeSql(), EXTR_OVERWRITE);
            $this->makeSql();
            $this->checkFields($this->meta['data']);

            if ($this->errors->__invoke()) {
                return $this;
            }

            // делаем строку подобную title, text, author, category
            $insertions = implode($this->meta['separator'], array_flip($this->meta['cols']));
            // делаем строку подобную :title, :text, :author, :category
            $values = implode($this->meta['separator'], array_keys($this->meta['data']));
            // создаем шаблон запроса вида INSERT INTO news (title,text,author,category) VALUES (:title,:text,:author,:category)
            $sql = "INSERT INTO {$this->meta['table']} ($insertions) VALUES ($values)";

            $db = new Db();
            $db->execute($sql, $this->meta['data']);
            $this->id = $db->getLastId();
            unset($this->meta);
            return $this;
        }

        /**
         * обновляет уже существующую запись, , которая ранее была получена из базы данных по id
         * @return Govno
         */
        protected function update() : static
        {
            //   TODO Как реализовать обновление только того поля, которое было изменено?
            $this->makeSql();
            $this->checkFields($this->meta['data']);

            if ($this->errors->__invoke()) {
                return $this;
            }

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
         * удаляет запись из БД
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
         * Определяет, является ли запись новой или уже существующей.
         * Если запись новая, то вызывает метод <b>insert</b>, в противном случае вызывает метод <b>update</b>.
         * @return Govno
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
         * Возвращает ассоциативный массив с данными: <ul>
         * <li><b>table</b> - имя таблицы;</li>
         * <li><b>cols</b> - массив вида 'index' => ':index' для подготовленных запросов;</li>
         * <li><b>data</b> - массив данных для подстановки  (':user' => 'Ahmed');</li>
         * <li><b>separator</b> - символ-разделитель, использюущийся в запросе;</li></ul>
         *
         * @return void
         */
        protected function makeSql() : void
        {
            // удаляем значения id, date итд не являющиеся строками и генерируемые БД автоматически или выполняющие служебные цели
            $fields = array_filter(get_object_vars($this), static function ($var) { return is_string($var); });
            $this->meta['cols'] = $this->meta['data'] = [];

            foreach ($fields as $index => $value) {
                $this->meta['cols'][$index] = ":$index"; // массив вида 'index' => ':index'
                $this->meta['data'][":$index"] = $value; // данные для внедрения ':user' => 'Ahmed'
            }

            $this->meta['separator'] = ', '; // разделитель, используемый в запросе
            $this->meta['table'] = static::TABLE_NAME; // имя таблицы в БД
        }

        /**
         * метод проверяет массив $data на наличие пустых элементов. По итогу работы создает массив,
         * с указанием всех пропущенных полей
         * @param array $data
         * @return void
         */
        protected function checkFields (array $data) : void
        {
            $this->errors = new UsersErrors();
            foreach ($data as $index => $datum) {
                if (empty($datum)) {
                    $this->errors->add($this->replacements[$index]);
                }
            }
        }

        /**
         * возвращает <b>string</b> с именем текущей таблицы
         * @return string
         */
        public static function getTableName() : string
        {
            return static::TABLE_NAME;
        }
    }
