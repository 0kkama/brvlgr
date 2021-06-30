<?php

    use App\classes\Config;

    // фуя перехвата ошибок для set_error_handler
    function err_catcher(int $errNo, string $errMsg, string $errFile, string $errLine) : void
    {
        $currentDate = date('Y-m-d');
        $currentTime = date('H:i:s');
        $errPath = __DIR__ . '/../logs/errors';
//
            $msgStr = "$currentTime - $errNo - $errMsg in $errFile line:$errLine";
            error_log("$msgStr\n", 3, "$errPath/err_$currentDate.log");
            echo 'ERROR!';
    }

    function var_dump_pre($mixed = null)
    {
        echo '<pre>';
        var_dump($mixed);
        echo '</pre>';
        return null;
    }

    function MakePrettyException(Exception $e) {
        $trace = $e->getTrace();

        $result = 'Exception: "';
        $result .= $e->getMessage();
        $result .= '" @ ';

        if ( $trace[0]['class'] !== '' ) {
            $result .= $trace[0]['class'];
            $result .= '->';
        }
        $result .= $trace[0]['function'];
        $result .= '();<br />';

        return $result;
    }


