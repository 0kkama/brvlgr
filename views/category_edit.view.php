<?php
    /**@var App\classes\models\Categories $cat */
    /**@var App\classes\utility\containers\ErrorsContainer $errors */
?>
<?php if($errors->notEmpty()): ?>
    <div class="alert alert-warning">
        <p> <?= $errors ?></p>
    </div>
<?php endif; ?>
<form method="post">
    <div class="form-group">
        <label> Добавить название:
            <input type="text" name="title" class="form-control" value="<?= $cat->getTitle() ?: ''?>" size="40" placeholder="Название">
        </label>
    </div>
    <div class="form-group">
        <label> Добавить URL:
            <input type="text" name="url" class="form-control" value="<?= $cat->getUrl() ?: ''?>" size="40" placeholder="URL">
        </label>
    </div>
    <button type="submit" class="btn btn-primary">Обновить</button>
</form>

