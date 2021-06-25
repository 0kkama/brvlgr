<?php    /** @var App\classes\models\Article $article  */
    /** @var App\classes\models\User $author */
?>
<div id="content">
    <?php foreach($news as $article): ?>
        <div class="article-preview">
            <h1><?=$article->title?></h1>
            <blockquote><i> <?= $article->date .'<br>'. 'Автор: ' . $article->author()->login . '<br>' .' Категория: ' . $article->category?></i></blockquote>
            <p><?=$article->getBriefContent()?></p>
            <a href="/article/read/<?=$article->id?>">Читать далее...</a>
        </div>
    <?php endforeach; ?>
</div>
