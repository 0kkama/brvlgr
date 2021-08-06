<?php
 /** @var $candidate \App\classes\models\User
  * @var $errors \App\classes\utility\ErrorsContainer
  */
 ?>
<form method="post">
    <?php if($errors->notEmpty()): ?>
        <hr>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <label for="auth-name">Имя</label>
        <input type="text" class="form-control" id="auth-login" name="firstName" value="<?=$candidate->getFirstName() ?? ''?>">
    </div>
    <div class="form-group">
        <label for="auth-surname">Отчество</label>
        <input type="text" class="form-control" id="auth-login" name="middleName" value="<?=$candidate->getMiddleName() ?? ''?>">
    </div>
    <div class="form-group">
        <label for="auth-surname">Фамилия</label>
        <input type="text" class="form-control" id="auth-login" name="lastName" value="<?=$candidate->getLastName() ?? ''?>">
    </div>
    <div class="form-group">
        <label for="auth-login">Логин</label>
        <input type="text" class="form-control" id="auth-login" name="login" value="<?=$candidate->getLogin() ?? ''?>">
    </div>
    <div class="form-group">
        <label for="auth-password">Пароль</label>
        <input type="password" class="form-control" id="auth-password" name="password1" value="">
    </div>
    <div class="form-group">
        <label for="auth-password">Повторите пароль</label>
        <input type="password" class="form-control" id="auth-password" name="password2" value="">
    </div>
    <div class="form-group">
        <label for="auth-email">Email</label>
        <input type="text" class="form-control" id="auth-email" name="email" value="<?=$candidate->getEmail() ?? ''?>">
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="login-remember" name="remember">
        <label class="form-check-label" for="login-remember">
            Запомнить меня на один месяц
        </label>
    </div>
    <hr>
    <button class="btn btn-primary">Зарегистрироваться</button>
</form>
