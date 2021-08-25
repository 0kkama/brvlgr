<?php
    /** @var App\classes\models\Article $article  */
    /** @var App\classes\models\ViewArticle $article  */
    /** @var array $news  */
    /** @var App\classes\models\User $author */
?>
<div id="content">
    <?php foreach($news as $article): ?>
        <div class="article-preview">
            <h1><?=$article->getTitle()?></h1>
            <blockquote><i> <?= $article ?></i></blockquote>
            <p><?=$article->getBriefContent()?></p>
            <a href="/article/read/<?=$article->getId()?>"> Читать далее...</a>
        </div>
    <?php endforeach; ?>
</div>
