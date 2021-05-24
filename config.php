<?php

    return
    [
        'db' =>
            [
                'host' => 'localhost',
                'name' => 'profit',
                'user' => 'admin',
                'pass' => '14133788',
                'char' => 'utf8',
            ],

        'CONSTANTS' =>
            [
                'PROTOCOL' => $_SERVER['SERVER_PROTOCOL'],
                'BASE_URL' => '/',
                'AUTH_LOG_PATH' => __DIR__ . '/logs/auth/',
                'PATH_FOR_IMG' => __DIR__ . '/resources/img/cats/',
                'PATH_TO_ARTICLES' => __DIR__ . '/resources/articles/',
                'PATH_TO_SESSIONS' => __DIR__ . '/resources/sessions.json',
                'PATH_TO_RECORDS' => __DIR__ . '/resources/txt/guests.json',
                'PATH_TO_TRACE' => __DIR__ . '/logs/trace/',
                'PATH_TO_TEMPLATES' => __DIR__ . '/views/',
            ],
    ];
