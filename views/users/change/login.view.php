<?php
    /** @var App\classes\models\User $user */
    /** @var App\classes\utility\containers\ErrorsContainer $errors */
    /** @var App\classes\utility\containers\FormsWithData $forms */
?>
<div> Текущий логин: <?= $user->getLogin()?> </div>
<form method="post">
    <div class="form-group">
        <label for="auth-login">Введите новый логин</label>
        <input type="text" class="form-control" id="auth-login" name="login" value="<?=$forms->get('login') ?? ''?>">
    </div>
    <hr>
    <button class="btn btn-primary">Изменить</button>
    <?php if($errors->notEmpty()) : ?>
        <hr>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>
</form>
