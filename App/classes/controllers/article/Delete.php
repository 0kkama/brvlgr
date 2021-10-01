<?php

    namespace App\classes\controllers\article;

    use App\classes\abstract\controllers\Controller;
    use App\classes\models\view\ViewNotArchivedArticles;
    use App\classes\utility\articles\ArticleRepresentation as Representation;
    use App\classes\utility\View;

    class Delete extends Controller
    {
        public function __construct(array $params, View $template)
        {
            parent::__construct($params, $template);
            $this->id = $this->params['id'];
            $view = ViewNotArchivedArticles::findOneBy('id', $this->id);
            Representation::checkEditRights($this->user, $view);
            Representation::archiveArticle($this->id);
        }
    }
