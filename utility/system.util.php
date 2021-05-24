<?php

    function template (string $path, array $variables = []) : string
    {
        $supaDupaFullPathToTemplate = "views/$path";
        extract($variables, EXTR_OVERWRITE);
        ob_start();
        include($supaDupaFullPathToTemplate);
        return ob_get_clean();
    }

    function parseURI (string $uri)
    {
        $pattern = '/*.php/';
        preg_match($pattern, $uri);

    }
