<?php

    namespace App\classes\controllers\article;



    use App\classes\abstract\controllers\Controller;
    use App\classes\utility\ArticleRepresentation as Representation;
    use App\classes\utility\View;

    class Delete extends Controller
    {
        protected Representation $representation;

        public function __construct(array $params, View $template)
        {
            parent::__construct($params, $template);
            $this->representation = new Representation();
            $this->id = $this->params['id'];
            $this->representation->archiveArticle($this->id);
        }
    }
