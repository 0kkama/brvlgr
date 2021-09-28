<?php
    /** @var \App\classes\models\view\ViewAllArticles $article */
    /** @var \App\classes\utility\containers\ErrorsContainer $errors */
    /** @var array $articles */
?>
<?php if($errors->notEmpty()): ?>
    <div class="alert alert-warning">
        <p> <?= $errors ?></p>
    </div>
<?php endif; ?>
 <table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Content</th>
        <th>Author</th>
        <th>ID</th>
        <th>Category</th>
        <th>Moder</th>
        <th>Watch</th>
        <th>Edit</th>
        <th>Hide</th>
        <th>Regain</th>
        <th>Archive</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($articles as $article): ?>
        <tr>
            <td><?=$article->getId()?></td>
            <td><?=$article->getTitle()?></td>
            <td><?=$article->getBriefContent(20)?></td>

            <td><?=$article->getLogin()?></td>
            <td><?=$article->getUserId()?></td>
            <td><?=$article->getCategory()?></td>
            <td><?=$article->getModer()?></td>
            <td><a href="/article/read/<?=$article->getId()?>">W</a></td>
            <td><a href="/article/update/<?=$article->getId()?>">E</a></td>
            <td><a href="/overseer/articles/hide/<?=$article->getId()?>">H</a></td>
            <td><a href="/overseer/articles/public/<?=$article->getId()?>">R</a></td>
            <td><a href="/overseer/articles/archive/<?=$article->getId()?>">A</a></td>
            <td><a href="/overseer/articles/delete/<?=$article->getId()?>">D</a></td>
        </tr>
    <?php endforeach;?>
 </table>
