<?php
/** @var  App\classes\models\User $user */
/** @var  App\classes\utility\containers\NavigationBar $menu */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?= $title ?? '' ?></title>
    <meta charset="utf-8" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/styles.css"/>

</head>

<body>
<div class="root">
    <header id='header'>
    <?= ($user->exist()) ? $user->getLogin() : '' ?>
    </header>

    <main>
        <div class="container">
            <div class="main-content">
                <aside id='nav'>
                    <nav>
                        <ul>
                           <?= $menu('aside') ?>
                        </ul>
                    </nav>
                </aside>
                <div style="flex-grow: 1">
                <?= $content  ?? ''?>
                </div>
            </div>
        </div>
    </main>

    <footer id="footer">
        <div>
            <ul>
                <?= $menu('footer') ?>
            </ul>
        </div>
        &copy 2020  &#8211 2021
    </footer>
    <div>
</body>
</html>
