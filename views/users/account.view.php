<?php
//    todo DENISKA
    /** @var \App\classes\models\User $user */
?>
<table>
    <tr>
        <th>Логин</th>
        <th><?=$user->getLogin() ?></th>
    </tr>
    <tr>
        <th>Имя</th>
        <th><?=$user->getFirstName() ?></th>
    </tr>
    <tr>
        <th>Фамилия</th>
        <th><?=$user->getMiddleName() ?></th>
    </tr>
    <tr>
        <th>Отчество</th>
        <th><?=$user->getLastName() ?></th>
    </tr>
    <tr>
        <th>Email</th>
        <th><?=$user->getEmail() ?></th>
    </tr>
    <tr>
        <th>Дата</th>
        <th><?=$user->getDate() ?></th>
    </tr>
</table>

<ul>
    <li><a href="/user/change?property=login">Изменить логин</a></li>
    <li><a href="/user/change?property=pass">Изменить пароль</a></li>
    <li><a href="/user/change?property=email">Изменить почтовый ящик</a></li>
    <li><a href="/user/change?property=fullname">Изменить ФИО</a></li>
    <li><a href="/user/articles?q=all">Мои статьи</a></li>
</ul>
