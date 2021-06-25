<?php /** @var App\classes\UsersErrors $loginErr */ ?>
<form method="post">
    <div class="form-group">
        <label for="auth-login">Логин</label>
        <input type="text" class="form-control" id="auth-login" name="login">
    </div>
    <div class="form-group">
        <label for="auth-password">Пароль</label>
        <input type="password" class="form-control" id="auth-password" name="password">
    </div>

    <hr>
    <button class="btn btn-primary">Войти</button>
    <?php if($loginErr()) : ?>
        <hr>
        <div class="alert alert-danger">
            <?= $loginErr ?>
            <?php unset($loginErr) ?>
        </div>
    <?php endif; ?>
</form>
