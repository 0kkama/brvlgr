<?php
    use App\classes\utility\Config;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\models\Article;
    /** @var Article $article  */
    /** @var ErrorsContainer $errors  */
?>
<div id="content">
    <?php if($errors->notEmpty()): ?>
        <div class="alert alert-warning">
            <p> <?= $errors ?></p>
        </div>
    <?php endif; ?>

        <form method="post">
            Заголовок статьи:<br>
            <div class="form-group">
                <input type="text" name="title" class="form-control" value="<?=$article->title ?? '' ?>" size="40" placeholder="Title">
            </div>
            Содержимое статьи:<br>
            <div class="form-group">
                <textarea name="text" placeholder="Content" cols="57" class="form-control" rows="30"><?=$article->text ?? '' ?></textarea>
            </div>
            Введите категорию статьи: <br>
            <div class="form-group">
                <input type="text" name="category" class="form-control" value="<?=$article->category  ?? '' ?>" size="40" placeholder="Category">

            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
        <hr>
        <a href="<?=Config::getInstance()->BASE_URL?>">Вернуться на главную</a>
</div>
