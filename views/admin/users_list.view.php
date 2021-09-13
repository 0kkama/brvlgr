<?php
    /** @var array $users */
    /** @var \App\classes\models\User $user */
    /** @var \App\classes\utility\containers\ErrorsContainer $errors */
?>
<?php if($errors->notEmpty()): ?>
    <div class="alert alert-warning">
        <p> <?= $errors ?></p>
    </div>
<?php endif; ?>
<table>
    <tr>
        <th>ID</th>
        <th>Login</th>
        <th>First</th>
        <th>Middle</th>
        <th>Last</th>
        <th>Email</th>
        <th>Rights</th>
        <th>Date</th>
        <th>Ban</th>
        <th>Regain</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?=$user->getId()?></td>
            <td><?=$user->getLogin()?></td>
            <td><?=$user->getFirstName()?></td>
            <td><?=$user->getMiddleName()?></td>
            <td><?=$user->getLastName()?></td>
            <td><?=$user->getEmail()?></td>
            <td><?=$user->getRights()?></td>
            <td><?=$user->getDate()?></td>
            <td><a href="/overseer/users/ban/<?=$user->getId()?>">B</a></td>
            <td><a href="/overseer/users/regain/<?=$user->getId()?>">R</a></td>
        </tr>
    <?php endforeach;?>
</table>
