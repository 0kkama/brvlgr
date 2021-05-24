<main>
    <div id="content">
        <article class="article-preview">
            <h1><?= $title ?></h1>
            <blockquote><i> <?= $article->getDate() .'<br>'. 'Автор: ' . $article->getAuthor() . '<br>' .' Категория: ' . $article->getCategory()?></i></blockquote>
            <br>
            <?= $article->getText() ?>
            <hr>

            <a href="\edit.php?id=<?= $article->getId() ?>"> Редактировать </a>

            <a href="\delete.php?id=<?= $article->getId() ?>"> Удалить </a>
        </article>
    </div>
</main>
