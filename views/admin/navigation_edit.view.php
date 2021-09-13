<?php
    /**@var App\classes\models\Navigation $navi */
    /**@var App\classes\utility\containers\ErrorsContainer $errors */
?>
<?php if($errors->notEmpty()): ?>
    <div class="alert alert-warning">
        <p> <?= $errors ?></p>
    </div>
<?php endif; ?>
<form method="post">
    <div class="form-group">
        <label> Изменить название:
            <input type="text" name="title" class="form-control" value="<?= $navi->getTitle() ?: ''?>" size="40" placeholder="Название">
        </label>
    </div>
    <div class="form-group">
        <label> Изменить URL:
            <input type="text" name="url" class="form-control" value="<?= $navi->getUrl() ?: ''?>" size="40" placeholder="/some/test">
        </label>
        <div class="form-group">
        <label> Установить порядковый номер:
            <input type="text" name="order" class="form-control" value="<?= $navi->getOrder() ?: ''?>" size="40" placeholder="123">
        </label>
    </div>
    <button type="submit" class="btn btn-primary">Обновить</button>
</form>
