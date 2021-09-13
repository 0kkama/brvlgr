<?php

    namespace App\classes\controllers\admin;

    use App\classes\abstract\controllers\Controller;
    use App\classes\models\AdminMenu as MenuModel;
    use App\classes\utility\containers\AdminMenuContainer;
    use App\classes\utility\View;

    class Index extends Controller
    {
        protected AdminMenuContainer $adminBar;
        protected string $title = 'Меню администратора';

        public function __construct(array $params, View $templateEngine)
        {
            ($this->adminBar = new AdminMenuContainer())->addArray(MenuModel::getAllBy());
            parent::__construct($params, $templateEngine);
            $this->content = $this->page->assign('menu', $this->adminBar)->render('admin/adminmenu');
        }


    }
