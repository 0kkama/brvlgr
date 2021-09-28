<?php

    namespace App\classes\utility\containers;

    use App\interfaces\ExtractDataInterface;
    use App\interfaces\ViewArticleInterface;
    use App\traits\ExtractDataTrait;

    class FormsForArticleData extends FormsWithData implements ExtractDataInterface
    {
        use ExtractDataTrait;
    }
