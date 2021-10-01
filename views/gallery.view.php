<?php
//    todo DENISKA
    use App\classes\models\User;
    use App\classes\utility\containers\ErrorsContainer;
    /** @var ErrorsContainer $errMsg
    * @var User $user
     * @var array $list
     */
?>
<div id="content">
    <?php if($errMsg->notEmpty()): ?>
        <div class="alert alert-danger">
            <?=$errMsg?>
        </div>
    <?php endif;?>

    <h1>Галерея</h1>
    <?php if ($user()):?>
        <form  method="post" enctype="multipart/form-data">
            <label for="formFile" class="form-label">Добавить изображение</label>
            <div style="display: flex">
                <input class="form-control" type="file" id="formFile" name="newimage" style="padding: 3px">
                <button class="btn btn-primary" style="margin-left: 10px">Отправить</button>
            </div>
        </form>
    <?php endif;?>

    <div style="display: flex; flex-wrap: wrap; justify-content: center">
    <?php foreach($list as $number => $cat): ?><a href=<?="image/$number"?>><img src="<?=$cat?>" alt="<?="Котик номер $number"?>" height="200" width="200" hspace="20" vspace="20" border="0"></a><?php endforeach; ?>
    </div>
</div>



