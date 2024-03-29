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
 <table class="table table-bordered" style="font-size: 12px">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>URL</th>
        <th>Order</th>
        <th>Status</th>
        <th>Edit</th>
        <th>Forbid</th>
        <th>Main</th>
        <th>Noname</th>
        <th>User</th>
        <th>Admin</th>
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
            <td><a href="/overseer/navigation/forbid/<?=$navi->getId()?>">F</a></td>
            <td><a href="/overseer/navigation/main/<?=$navi->getId()?>">M</a></td>
            <td><a href="/overseer/navigation/noname/<?=$navi->getId()?>">N</a></td>
            <td><a href="/overseer/navigation/user/<?=$navi->getId()?>">U</a></td>
            <td><a href="/overseer/navigation/admin/<?=$navi->getId()?>">A</a></td>
            <td><a href="/overseer/navigation/delete/<?=$navi->getId()?>">D</a></td>
        </tr>
    <?php endforeach;?>
 </table>

<form method="post" style="padding: 0">
    <div class="form-row">
        <div class="col-6">
            <label style="width: 100%"> Добавить название:
                <input type="text" name="title" class="form-control" value="<?= $navigation->getTitle() ?: ''?>" size="40" placeholder="Название">
            </label>
        </div>
        <div class="col-6">
            <label style="width: 100%"> Добавить URL:
                <input type="text" name="url" class="form-control" value="<?= $navigation->getUrl() ?: ''?>" size="40" placeholder="/some/navigation">
            </label>
        </div>
        <div class="col-6">
            <label style="width: 100%"> Установить порядковый номер:
                <input type="text" name="order" class="form-control" value="<?= $navigation->getOrder() ?: ''?>" size="40" placeholder="123">
            </label>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Создать</button>
</form>

