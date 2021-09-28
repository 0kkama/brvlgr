<?php
    /** @var \App\classes\models\view\ViewAllArticles $article */
    /** @var array $articles */
?>
<div>
    <a href="/user/articles?q=all">Все</a>
    <a href="/user/articles?q=publ">Опубликованные</a>
    <a href="/user/articles?q=unpubl">Не опубликованные</a>
</div>
<div>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Category</th>
            <th>Status</th>
            <th>Watch</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php foreach ($articles as $article): ?>
            <tr>
                <td><?=$article->getId()?></td>
                <td><?=$article->getTitle()?></td>
                <td><?=$article->getBriefContent(50)?></td>
                <td><?=$article->getCategory()?></td>
                <td><?=($article->getModer() ? 'Опубл' : 'Неопубл')?></td>
                <td><a href="/article/read/<?=$article->getId()?>">R</a></td>
                <td><a href="/article/update/<?=$article->getId()?>">E</a></td>
                <td><a href="/article/delete/<?=$article->getId()?>">D</a></td>
            </tr>
        <?php endforeach;?>
    </table>
</div>
