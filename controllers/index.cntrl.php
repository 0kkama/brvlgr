<?php

    $title = 'Галерея';
    $list = glob('resources/img/cats/*');

    $catsList = [];
    foreach ($list as $index => $item) {
        $catsList[$index + 10] = $item;
    }

    $content = template('index.view.php',
        [
            'title' => $title,
            'list' => $catsList,
        ]
    );
