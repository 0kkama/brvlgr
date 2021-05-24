<div id="content">
    <?php if(false /*$sendStatus*/): ?>
        <p>Ваша статья была отправлена!</p>
        <a href="<?=BASE_URL?>">Вернуться на главную</a><br>
        <a href="<?=BASE_URL?>add">Добавить еще одну статью</a>
    <?php else: ?>

        <form method="post">
            Заголовок статьи:<br>
            <div class="form-group">
                <input type="text" name="title" class="form-control" value="<?=$article->getTitle() ?? '' ?>" size="40" placeholder="Title">
            </div>

                <!--            Автор статьи: --><?//=$article['author'] ?? '' ?><!-- <br>-->

            Содержимое статьи:<br>
            <div class="form-group">
                <textarea name="text" placeholder="Content" cols="57" class="form-control" rows="30"> <?=$article->getText() ?? '' ?> </textarea>
            </div>
            Введите категорию статьи: <br>
            <div class="form-group">
                <input type="text" name="category" class="form-control" value="<?=$article->getCategory()  ?? '' ?>" size="40" placeholder="Category">

            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>

        <hr>
        <a href="<?=BASE_URL?>">Вернуться на главную</a>
    <?php endif; ?>
</div>
