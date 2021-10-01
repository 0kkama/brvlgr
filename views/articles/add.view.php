<?php

    use App\classes\utility\Config;
    use App\classes\utility\containers\CategoriesList;
    use App\classes\utility\containers\ErrorsContainer;
    use App\classes\utility\containers\FormsForArticleData;

    /** @var FormsForArticleData $forms  */
    /** @var ErrorsContainer $errors  */
    /** @var CategoriesList $categories  */
?>
<div id="content">
    <?php if($errors->notEmpty()): ?>
        <div class="alert alert-warning">
            <p> <?= $errors ?></p>
        </div>
    <?php endif; ?>

    <div class="article-form-container">
        <form method="post" id="article-forms">
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
            <button type="submit" class="btn btn-primary" id="send">Отправить</button>
        </form>
    </div>
        <hr>
        <a href="<?=Config::getInstance()->BASE_URL?>">Вернуться на главную</a>
</div>
