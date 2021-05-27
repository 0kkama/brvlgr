<?php
    use App\classes\Config;
    /** @var App\classes\publication\Article $article  */
?>
<div id="content">
    <?php if(false /*$sendStatus*/): ?>
        <p>Ваша статья была отправлена!</p>
        <a href="<?=Config::getInstance()->BASE_URL?>">Вернуться на главную</a><br>
        <a href="<?=Config::getInstance()->BASE_URL?>add">Добавить еще одну статью</a>
    <?php else: ?>

        <form method="post">
            Заголовок статьи:<br>
            <div class="form-group">
                <input type="text" name="title" class="form-control" value="<?=$article->title ?? '' ?>" size="40" placeholder="Title">
            </div>

                <!--            Автор статьи: --><?//=$article['author'] ?? '' ?><!-- <br>-->

            Содержимое статьи:<br>
            <div class="form-group">
                <textarea name="text" placeholder="Content" cols="57" class="form-control" rows="30"> <?=$article->text ?? '' ?> </textarea>
            </div>
            Введите категорию статьи: <br>
            <div class="form-group">
                <input type="text" name="category" class="form-control" value="<?=$article->category  ?? '' ?>" size="40" placeholder="Category">

            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>

        <hr>
        <a href="<?=Config::getInstance()->BASE_URL?>">Вернуться на главную</a>
    <?php endif; ?>
</div>
