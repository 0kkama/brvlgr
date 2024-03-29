<?php
    /**@var App\classes\utility\containers\CategoriesList $categories */
    /**@var App\classes\utility\containers\ErrorsContainer $errors */
    /**@var App\classes\models\Categories $category */
    /**@var App\classes\models\Categories $cat */
    /** */
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
        <th>Status</th>
        <th>Edit</th>
        <th>Hide</th>
        <th>Regain</th>
        <th>Delete</th>
    </tr>
    <?php foreach ($categories as $category): ?>
        <tr>
            <td><?=$category->getId()?></td>
            <td><?=$category->getTitle()?></td>
            <td><?=$category->getUrl()?></td>
            <td><?=$category->getStatus()?></td>
            <td><a href="/overseer/categories/edit/<?=$category->getId()?>">Edit</a></td>
            <td><a href="/overseer/categories/hide/<?=$category->getId()?>">Hide</a></td>
            <td><a href="/overseer/categories/regain/<?=$category->getId()?>">Regain</a></td>
            <td><a href="/overseer/categories/delete/<?=$category->getId()?>">Delete</a></td>
        </tr>
    <?php endforeach;?>
</table>

<form method="post" style="padding: 0">
    <div class="form-row">
        <div class="col-6">
            <label> Добавить название:
                <input type="text" name="title" class="form-control" value="<?= $cat->getTitle() ?: ''?>" size="40" placeholder="Название">
            </label>
        </div>
        <div class="col-6">
            <label> Добавить URL:
                <input type="text" name="url" class="form-control" value="<?= $cat->getUrl() ?: ''?>" size="40" placeholder="URL">
            </label>
        </div>
    </div>

        <button type="submit" class="btn btn-primary">Создать</button>
</form>
