<?php
    use App\classes\models\User;
    use App\classes\MyErrors;
    /** @var MyErrors $errMsg */
    /** @var User $user */
?>
<div id="content">
    <?php if($errMsg()): ?>
        <div class="alert alert-danger">
            <?=$errMsg?>
        </div>
    <?php endif;?>
    <h1>Галерея</h1>
    <?php foreach($list as $number => $cat): ?><a href=<?="/?cntrl=image&id=$number"?>><img src="<?=$cat?>" alt="<?="Котик номер $number"?>" height="200" width="200" hspace="20" vspace="20" border="0"></a><?php endforeach; ?>
</div>
<?php if ($user()):?>
<form  method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="formFile" class="form-label">Добавить изображение</label>
        <input class="form-control" type="file" id="formFile" name="newimage">
    </div>
        <hr>
    <button class="btn btn-primary">Отправить</button>
</form>
<?php endif;?>



