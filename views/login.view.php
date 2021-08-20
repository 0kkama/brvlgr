<?php /** @var App\classes\utility\ErrorsContainer $errors */ ?>
<?php /** @var App\classes\models\User $candidate */ ?>
<form method="post">
    <div class="form-group">
        <label for="auth-login">Логин</label>
        <input type="text" class="form-control" id="auth-login" name="login" value="<?=$candidate->getLogin() ?? ''?>">
    </div>
    <div class="form-group">
        <label for="auth-password">Пароль</label>
        <input type="password" class="form-control" id="auth-password" name="password">
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="login-remember" name="remember">
        <label class="form-check-label" for="login-remember">
            Запомнить меня
        </label>
    </div>

    <hr>
    <button class="btn btn-primary">Войти</button>
    <?php if($errors->notEmpty()) : ?>
        <hr>
        <div class="alert alert-danger">
            <?= $errors ?>
            <?php unset($errors) ?>
        </div>
    <?php endif; ?>
</form>
