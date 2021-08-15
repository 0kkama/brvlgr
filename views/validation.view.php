<?php /** @var App\classes\utility\ErrorsContainer $errors */ ?>
<form method="post">
    <div class="form-group">
        <label for="auth-login">Логин</label>
        <input type="text" class="form-control" id="auth-login" name="login">
    </div>
    <div class="form-group">
        <label for="auth-token">Проверочный код</label>
        <input type="text" class="form-control" id="auth-token" name="token" placeholder="Введите код, отправленный на указанный вами почтовый ящик">
    </div>

    <hr>
    <button class="btn btn-primary">Подтвердить</button>
    <?php if($errors->notEmpty()) : ?>
        <hr>
        <div class="alert alert-danger">
            <?= $errors ?>
            <?php unset($errors) ?>
        </div>
    <?php endif; ?>
</form>
