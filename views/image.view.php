<?php /** @var App\classes\controllers\Image $image */?>
<main>
    <h1>Изображение</h1>
    <div  id="content">
        <img src="<?= '/../'.$image->getImage()?>" height="600" width="600" border="0">
    </div>
    <a href="/gallery"> Назад в галерею </a>
    <a href="/image/download/<?=$image->getId()?>"> скачать </a>
</main>
