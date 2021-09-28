<?php
    /** @var App\classes\models\User $user */
    /** @var App\classes\utility\containers\ErrorsContainer $errors */
    /** @var App\classes\utility\containers\FormsWithData $forms */
?>
<div> Текущие ФИО: <?= $user->getFullName()?> </div>
<form method="post">
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
    <hr>
    <button class="btn btn-primary">Изменить</button>
    <?php if($errors->notEmpty()) : ?>
        <hr>
        <div class="alert alert-danger">
            <?= $errors ?>
        </div>
    <?php endif; ?>
</form>
