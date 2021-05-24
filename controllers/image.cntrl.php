<?php

    if (!isset($_GET['id'])) {
        echo 'Error!'; exit();
    }

    $imgID = $_GET['id'];
    $list = glob('resources/img/cats/*');
    $catsList = [];

    foreach ($list as $index => $item) {
        $catsList[$index + 10] = $item;
    }

    $title = 'Изборажение';
    $content = template($title,
       [
           'title' => $title,
           'imageID' => $imgID,
       ]
    );

