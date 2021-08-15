<?php /** @var App\classes\utility\ErrorsContainer $errors */ ?>
<form method="post">
    <div class="form-group">
        <label for="auth-login">Логин</label>
        <input type="text" class="form-control" id="auth-login" name="login">
    </div>
    <div class="form-group">
        <label for="auth-email">Email</label>
        <input type="text" class="form-control" id="auth-token" name="token" placeholder="">
    </div>

    <hr>
    <button class="btn btn-primary">Получить новый код</button>
    <?php if($errors->notEmpty()) : ?>
        <hr>
        <div class="alert alert-danger">
            <?= $errors ?>
            <?php unset($errors) ?>
        </div>
    <?php endif; ?>
</form>
