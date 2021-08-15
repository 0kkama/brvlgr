<?php


    namespace App\classes\abstract;


    use App\classes\controllers\Error;
    use App\classes\exceptions\CustomException;
    use App\classes\exceptions\ExceptionWrapper;
    use App\classes\models\Article;
    use App\classes\utility\ErrorsContainer;
    use App\classes\View;
    use App\classes\models\User;
    use App\classes\Config;

    abstract class ControllerActing extends Controller
    {
        protected function action(string $action) : void
        {
            if (method_exists($this, $action)) {
                $this->$action();
            } else {
                Error::deadend(400);
            }
        }
    }
