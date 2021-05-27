<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? '' ?></title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/styles.css" />
</head>

<body>
<div class="root">
    <header id='header'>
    <?=$name ?? ''?>
    </header>

    <main>
        <div class="container">
            <div class="main-content">
                <aside id='nav'>
                    <nav>
                        <ul>
                            <li> <a href="\login.php"> Войти </a> </li>
                            <li> <a href="\index.php"> Главная </a> </li>
                            <li> <a href="\news.php"> Новости </a> </li>
                            <li> <a href="\gallery.php"> Котики </a> </li>
                            <li> <a href="\add.php"> Добавить </a> </li>

                        <?php for($i = 0 ; $i < 4 ; $i++): ?>
                                <li> Navigation </li>
                        <?php endfor; ?>

                            <li> <a href="\logout.php"> Выйти </a> </li>
                            <li> <a href="\test.php"> Тест </a> </li>
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
