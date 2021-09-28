<?php
    /** @var App\classes\models\User $author */
    /** @var \App\classes\models\view\ViewPublishedArticles $article */
?>
<main>
    <div id="content">
       <article class="article-preview">
            <h1><?= $article->getTitle() ?></h1>
            <blockquote><i> <?= $article ?></i></blockquote>
            <br>
            <?= $article->getFormattedContent() ?>
            <hr>
        </article>
    </div>
</main>
