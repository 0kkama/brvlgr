<?php
    /** @var App\classes\utility\containers\ErrorsContainer $errors */
    /** @var App\classes\utility\containers\FormsWithData $forms */
?>
Смена пароля
<form method="post">
    <div class="form-group">
        <label for="auth-password">Пароль</label>
        <input type="password" class="form-control" id="auth-password" name="password1" value="">
    </div>
    <div class="form-group">
        <label for="auth-password">Повторите пароль</label>
        <input type="password" class="form-control" id="auth-password" name="password2" value="">
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
