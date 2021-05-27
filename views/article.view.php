<?php    /** @var App\classes\publication\Article $article  */
        /** @var App\classes\publication\User $author */
?>
<main>
    <div id="content">
        <article class="article-preview">
            <h1><?= $title ?></h1>
            <blockquote><i> <?= $article->date .'<br>'. 'Автор: ' . $author->login . '<br>' .' Категория: ' . $article->category?></i></blockquote>
            <br>
            <?= $article->text ?>
            <hr>

            <a href="\edit.php?id=<?= $article->id ?>"> Редактировать </a>

            <a href="\delete.php?id=<?= $article->id ?>"> Удалить </a>
        </article>
    </div>
</main>
