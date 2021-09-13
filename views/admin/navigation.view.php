<?php
    /** @var \App\classes\utility\containers\NavigationBar $naviBar */
    /** @var \App\classes\models\Navigation $navi */
    /** @var \App\classes\models\Navigation $navigation */
    /** @var \App\classes\utility\containers\ErrorsContainer $errors */
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
        <th>URL</th>
        <th>Order</th>
        <th>Status</th>
        <th>Edit</th>
        <th>Hide</th>
        <th>Regain</th>
        <th>Private</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($naviBar as $navi): ?>
        <tr>
            <td><?=$navi->getId()?></td>
            <td><?=$navi->getTitle()?></td>
            <td><?=$navi->getUrl()?></td>
            <td><?=$navi->getOrder()?></td>
            <td><?=$navi->getStatus()?></td>
            <td><a href="/overseer/navigation/edit/<?=$navi->getId()?>">E</a></td>
            <td><a href="/overseer/navigation/hide/<?=$navi->getId()?>">H</a></td>
            <td><a href="/overseer/navigation/regain/<?=$navi->getId()?>">R</a></td>
            <td><a href="/overseer/navigation/private/<?=$navi->getId()?>">P</a></td>
            <td><a href="/overseer/navigation/delete/<?=$navi->getId()?>">D</a></td>
        </tr>
    <?php endforeach;?>
 </table>

<form method="post">
    <div class="form-group">
        <label> Добавить название:
            <input type="text" name="title" class="form-control" value="<?= $navigation->getTitle() ?: ''?>" size="40" placeholder="Название">
        </label>
    </div>
    <div class="form-group">
        <label> Добавить URL:
            <input type="text" name="url" class="form-control" value="<?= $navigation->getUrl() ?: ''?>" size="40" placeholder="/some/navigation">
        </label>
    </div>
    <div class="form-group">
        <label> Установить порядковый номер:
            <input type="text" name="order" class="form-control" value="<?= $navigation->getOrder() ?: ''?>" size="40" placeholder="123">
        </label>
    </div>
    <button type="submit" class="btn btn-primary">Создать</button>
</form>
