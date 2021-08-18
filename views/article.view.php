<?php    /** @var App\classes\models\Article $article  */
        /** @var App\classes\models\User $author */
?>
<main>
    <div id="content">
       <article class="article-preview">
            <h1><?= $article->title ?></h1>
            <blockquote><i> <?= $article ?></i></blockquote>
            <br>
            <?= $article->getFormattedContent() ?>
            <hr>
            <a href="/article/edit/<?= $article->getId() ?>"> Редактировать </a>
            <a href="/article/delete/<?= $article->getId() ?>"> Удалить </a>
        </article>
    </div>
</main>
