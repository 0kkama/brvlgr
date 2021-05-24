<?php
    namespace App\classes;

    class GuestBook extends TextFile
    {

        public function append( array $newRecord ) : object
        {
            $this->fileContent[] = $newRecord;
            return $this;
        }

        public function save() : void
        {
            $wrapper = static function (array $chunk) : string {
                return json_encode($chunk,true) . "\n";
            };

            $newContent = array_map($wrapper, $this->fileContent);
            file_put_contents($this->pathToFile, $newContent);
        }
    }
