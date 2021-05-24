<div id="content">
    <?php foreach($news as $article): ?>
        <div class="article-preview">
            <h1><?=$article->getTitle()?></h1>
            <blockquote><i> <?= $article->getDate() .'<br>'. 'Автор: ' . $article->getAuthor() . '<br>' .' Категория: ' . $article->getCategory()?></i></blockquote>
            <p><?=$article->getBriefContent()?></p>
            <a href="/article.php?id=<?=$article->getId()?>">Читать далее...</a>
        </div>
    <?php endforeach; ?>
</div>
