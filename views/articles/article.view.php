<?php
    /** @var App\classes\models\User $author */
    /** @var App\classes\models\ViewPublishedArticles $article */
?>
<main>
    <div id="content">
       <article class="article-preview">
            <h1><?= $article->getTitle() ?></h1>
            <blockquote><i> <?= $article ?></i></blockquote>
            <br>
            <?= $article->getFormattedContent() ?>
            <hr>
            <a href="/article/update/<?= $article->getId() ?>"> Редактировать </a>
            <a href="/article/delete/<?= $article->getId() ?>"> Удалить </a>
        </article>
    </div>
</main>
