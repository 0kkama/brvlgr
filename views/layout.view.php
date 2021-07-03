<?php
/** @var  App\classes\models\User $user */
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
    <?= $user->getLogin() ?? ''?>
    </header>

    <main>
        <div class="container">
            <div class="main-content">
                <aside id='nav'>
                    <nav>
                        <ul>
                            <li> <a href="/login"> Войти </a> </li>
                            <li> <a href="/"> Главная </a> </li>
                            <li> <a href="/news"> Новости </a> </li>
                            <li> <a href="/gallery"> Котики </a> </li>
                            <li> <a href="/article/add"> Добавить </a> </li>

                        <?php for($i = 0 ; $i < 4 ; $i++): ?>
                                <li> Navigation </li>
                        <?php endfor; ?>

                            <li> <a href="/logout"> Выйти </a> </li>
                            <li> <a href="/test.php"> Тест </a> </li>
                            <li> <a href="/info.php"> Инфо </a> </li>
                        </ul>
                    </nav>
                </aside>

                <?=$content  ?? ''?>

            </div>
        </div>
    </main>

    <footer id="footer">

        <div>
            <ul>
                <?php for($i = 0 ; $i < 6 ; $i++): ?>
                    <li style='display:inline;margin-right:15px'> Navigation </li>
                <?php endfor; ?>

            </ul>
        </div>
        &copy 2020  &#8211 2021
    </footer>
    <div>
</body>
</html>
