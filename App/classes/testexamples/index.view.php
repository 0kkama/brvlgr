<div id="content">
    <?php foreach($messages as $message): ?>
        <div class="article-preview">
            <blockquote><i><?=$message['name']?> </i></blockquote>
            <h1><?=$message['message']?></h1>

        </div>
    <?php endforeach; ?>
</div>

<form method="post">
    <div class="form-group">
        <label for="auth-login">Имя</label>
        <input type="text" class="form-control" id="auth-login" name="name">
    </div>
    <div class="form-group">
        <label for="auth-password">Сообщение</label>
        <input type="text" class="form-control" id="auth-password" name="message">
    </div>
    <hr>
    <button class="btn btn-primary">Отправить</button>
</form>