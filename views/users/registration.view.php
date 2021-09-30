<?php
    /** @var $candidate \App\classes\models\User */
    /** @var $forms \App\classes\utility\containers\FormsWithData */
    /** @var $errors \App\classes\utility\containers\ErrorsContainer */
 ?>
<div class="registration-forms">
<form method="post" id="registration-forms">
    <?php if($errors->notEmpty()): ?>
        <hr>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <label for="auth-name">Имя</label>
        <input type="text" class="form-control" id="auth-name" name="firstName" value="<?=$forms->get('firstName') ?? ''?>">
    </div>
    <div class="form-group">
        <label for="auth-surname">Отчество</label>
        <input type="text" class="form-control" id="auth-surname" name="middleName" value="<?=$forms->get('middleName') ?? ''?>">
    </div>
    <div class="form-group">
        <label for="auth-lastname">Фамилия</label>
        <input type="text" class="form-control" id="auth-lastname" name="lastName" value="<?=$forms->get('lastName') ?? ''?>">
    </div>
    <div class="form-group">
        <label for="auth-login">Логин</label>
        <input type="text" class="form-control" id="auth-login" name="login" value="<?=$forms->get('login') ?? ''?>" placeholder="От 3 до 20 и может содержать цифры, буквы и символы _%+-!&()">
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
        <input type="text" class="form-control" id="auth-email" name="email" value="<?=$forms->get('email') ?? ''?>">
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="login-remember" name="remember">
        <label class="form-check-label" for="login-remember">
            Запомнить меня
        </label>
    </div>
    <hr>
    <button class="btn btn-primary">Зарегистрироваться</button>
</form>
</div>

