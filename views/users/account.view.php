<?php
//    todo DENISKA
    /** @var \App\classes\models\User $user */
?>

<div style="display: flex">
<table class="user-table">
    <tr>
        <th>Логин</th>
        <td><?=$user->getLogin() ?></td>
    </tr>
    <tr>
        <th>Имя</th>
        <td><?=$user->getFirstName() ?></td>
    </tr>
    <tr>
        <th>Фамилия</th>
        <td><?=$user->getMiddleName() ?></td>
    </tr>
    <tr>
        <th>Отчество</th>
        <td><?=$user->getLastName() ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?=$user->getEmail() ?></td>
    </tr>
    <tr>
        <th>Дата</th>
        <td><?=$user->getDate() ?></td>
    </tr>
</table>

<ul>
    <li><a href="/user/change?property=login">Изменить логин</a></li>
    <li><a href="/user/change?property=pass">Изменить пароль</a></li>
    <li><a href="/user/change?property=email">Изменить почтовый ящик</a></li>
    <li><a href="/user/change?property=fullname">Изменить ФИО</a></li>
    <li><a href="/user/articles?q=all">Мои статьи</a></li>
</ul>
</div>
