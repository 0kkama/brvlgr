<div id="content">
    <h1>Крутые коты</h1>
    <?php foreach($list as $number => $cat): ?><a href=<?="/image.php?id=$number"?>><img src="<?=$cat?>" alt="<?="Котик номер $number"?>" height="200" width="200" hspace="20" vspace="20" border="0"></a><?php endforeach; ?>
</div>
<?php if ($name !== null):?>
<form  method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="formFile" class="form-label">Добавить котика</label>
        <input class="form-control" type="file" id="formFile" name="newimage">
    </div>
        <hr>
    <button class="btn btn-primary">Отправить</button>
</form>
<?php endif;?>
<p><?=$errMsg?></p>
