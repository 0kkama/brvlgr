<?php

    namespace App\classes\utility\containers;

    use App\interfaces\ExtractDataInterface;
    use App\traits\ExtractDataTrait;

    class FormsForUserData extends FormsWithData implements ExtractDataInterface
    {
        use ExtractDataTrait;
    }
