<?php

    namespace App\classes\controllers\article;



    use App\classes\abstract\controllers\Controller;
    use App\classes\models\view\ViewNotArchivedArticles;
    use App\classes\utility\articles\ArticleRepresentation as Representation;
    use App\classes\utility\View;

    class Delete extends Controller
    {
        protected Representation $representation;

        public function __construct(array $params, View $template)
        {
            parent::__construct($params, $template);
            $this->id = $this->params['id'];
            $this->representation = new Representation();
            $view = ViewNotArchivedArticles::findOneBy('id', $this->id);
            $this->representation->checkEditRights($this->user, $view);
            $this->representation->archiveArticle($this->id);
        }
    }
