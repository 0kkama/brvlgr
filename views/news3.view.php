<div id="content">
    <?php foreach($news as $article): ?>
        <div class="article-preview">
            <h1><?=$article['title']?></h1>
            <blockquote><i> <?= $article['date'] .'<br>'. 'Автор: ' . $article['author'] . '<br>' .' Категория: ' . $article['category']?></i></blockquote>
            <p><?=$article['brief']?></p>
            <a href="/article.php?id=<?=$article['id']?>">Читать далее...</a>
        </div>
    <?php endforeach; ?>
</div>
