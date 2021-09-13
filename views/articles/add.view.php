<?php

    use App\classes\utility\Config;
    use App\classes\utility\containers\CategoriesList;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\FormsForArticle;

    /** @var FormsForArticle $forms  */
    /** @var ErrorsContainer $errors  */
    /** @var CategoriesList $categories  */
?>
<div id="content">
    <?php if($errors->notEmpty()): ?>
        <div class="alert alert-warning">
            <p> <?= $errors ?></p>
        </div>
    <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label> Заголовок статьи:
                    <input type="text" name="title" class="form-control" value="<?= $forms->get('title') ?: '' ?>" size="40" placeholder="Title">
                </label>
            </div>
            <div class="form-group">
                <label> Содержимое статьи:
                    <textarea name="text" placeholder="Content" cols="57" class="form-control" rows="30"><?=$forms->get('text') ?: '' ?></textarea>
                </label>
            </div>
            <div class="form-group">
                <label> Выберите категорию статьи:
                <select name='category' class="custom-select" size='1'>
                    <?= $categories($forms->get('category') ?: '') ?>
                </select>
                </label>
            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
        <hr>
        <a href="<?=Config::getInstance()->BASE_URL?>">Вернуться на главную</a>
</div>
