<?php
/** @var  App\classes\models\User $user */
/** @var  App\classes\utility\NavigationBar $menu */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title><?= $title ?? '' ?></title>
    <meta charset="utf-8" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/styles.css"/>
</head>

<body>
<div class="root">
    <header id='header'>
    <?= ($user->hasUserRights()) ? $user->getLogin() : '' ?>
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
                <?= $content  ?? ''?>
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
