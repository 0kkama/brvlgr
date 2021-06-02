<?php

    namespace App\classes;
//
//    use classes\Db;
//    use classes\publication\Article;
//    use App\interfaces\Singleton;
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
    abstract class Govno implements Shitty
    {

    /**
     *@const const TABLE_NAME dynamically changing in inheriting classes
     */
    protected const TABLE_NAME = 'Govno';

        /**
         * Finds needed line in table by given <b>$id</b> and return it like object of respective class
         * @param string $id
         * @return object|null
         */
        public static function findById(string $id) : ?object
        {
            $db = new Db();
            $sql = 'SELECT * FROM ' . static::TABLE_NAME . ' WHERE id = :id';
            $result = $db->queryOne($sql, ['id' => $id], static::class);
            return $result;
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
         * @return $this|null|array объект или null (в случае неудачи)
         */
        protected function insert() : null|Govno|Errors
        {
            extract($this->makeSql(), EXTR_OVERWRITE);

            //<editor-fold desc="Другой вариант">
            //            проверка заполненности всех полей объекта
            //        $wrapper = static function ($data) {
            //            foreach ($data as $index => $datum) {
            //                if (empty($datum)) {
            //                    echo "Не указан " . $index;
            //                    return true;
            //                }
            //            }
            //        };
            //
            //            if ( $wrapper($data)) {
            //                return null;
            //            }
            //</editor-fold>

            $errors = $this->checkFields($data);

            if ($errors()) {
                return $errors;
            }

            // делаем строку подобную title, text, author, category
            $insertions = implode($separator, array_flip($cols));
            // делаем строку подобную :title, :text, :author, :category
            //            $values = implode($separator, $cols);
            $values = implode($separator, array_keys($data));
            // создаем шаблон запроса вида INSERT INTO news (title,text,author,category) VALUES (:title,:text,:author,:category)
            $sql = "INSERT INTO $table ($insertions) VALUES ($values)";

            $db = new Db();
            $db->execute($sql, $data);
            $this->id = $db->getLastId();
            return $this;
        }

        /**
         * обновляет уже существующую запись, , которая ранее была получена из базы данных по id
         * @return $this|null
         */
        protected function update() : null|Govno|Errors
        {
            //   TODO Как реализовать обновление только того поля, которое было изменено?
            // удаляем все пустые поля, после чего передаем массив фу makeSql
            extract($this->makeSql(), EXTR_OVERWRITE);

            // проверяем заполненность всех полей и возвращаем ошибку при необходимости
            $errors = $this->checkFields($data);
            if ($errors()) {
                return $errors;
            }

            $set = [];
            foreach ($cols as $index => $value) {
                $set[] = "$index = $value";
            }

            $data[":id"] = $this->id;
            $set = implode($separator, $set);
            // шаблон подобный UPDATE news SET title = :title, text = :text, author = :author WHERE id = :id
            $sql = "UPDATE $table SET $set WHERE id = :id";

            var_dump($sql);
            $db = new Db();
            $db->execute($sql, $data);
            //  $this->id = $db->getLastId();
            return $this;
        }

        /**
         * удаляет запись из БД
         * @return bool|null
         */
        public function delete() : ?bool
        {
            /* TODO сделать его обычным или статическим? возможно, добавить деструктор? */
            //  if (isset($this->id) && (static::findById($this->id))) {
            if (isset($this->id)) {
                $table = static::TABLE_NAME;
                $sql = "DELETE FROM $table WHERE id = :id";
                $data[':id'] = $this->id;

                $db = new Db();
                return $db->execute($sql, $data);
            }
            return null;
        }

        /**
         * Определяет, является ли запись новой или уже существующей.
         * Если запись новая, то вызывает метод <b>insert</b>, в противном случае вызывает метод <b>update</b>.
         * @return $this|null|array
         */
        public function save() : null|Govno|Errors
        {
            //  необходима проверка на наличие такого id в БД
            //            if (isset($this->id) && (static::findById($this->id))) {
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
         * @return array
         */
        protected function makeSql() : array
        {
            // удаляем значения id, date итд не являющиеся строками и генерируемые БД автоматически
            $fields = array_filter(get_object_vars($this), static function ($var) { return is_string($var); });
            // var_dump($fields);
            $cols = $data = [];

            foreach ($fields as $index => $value) {
                $cols[$index] = ":$index";
                $data[":$index"] = $value;
            }

            $separator = ', ';
            $table = static::TABLE_NAME;

            return
                [
                    'table' => $table, // имя таблицы в БД
                    'cols' => $cols, // массив вида 'index' => ':index'
                    'data' => $data, // данные для внедрения ':user' => 'Ahmed'
                    'separator' => $separator,
                ];
        }

        /**
         * метод проверяет массив $data на наличие пустых элементов. По итогу работы возвращает массив,
         * с указанием всех пропущенных полей
         * @param array $data
         * @return array
         */
        protected function checkFields (array $data) : Errors
        {
            $emptyes = new Errors();
            foreach ($data as $index => $datum) {
                if (empty($datum)) {
                    $emptyes->add("Отсутствует " . $index . "<br>");
                }
            }
            return $emptyes;
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
