<?php
    /** @var App\classes\models\User $user */
    /** @var App\classes\utility\containers\ErrorsContainer $errors */
    /** @var App\classes\utility\containers\FormsWithData $forms */
?>
<div> Текущий email: <?= $user->getEmail()?> </div>
<form method="post">
    <div class="form-group">
        <label for="auth-email">Введите новый email</label>
        <input type="text" class="form-control" id="auth-email" name="email" value="<?=$forms->get('email') ?? ''?>">
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

