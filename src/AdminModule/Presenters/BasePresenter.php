<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use Nette\Application\UI\Presenter;


abstract class BasePresenter extends Presenter {

    protected function isPublic() : bool {
        return false;
    }

    protected function startup() {
        parent::startup();

        if (!$this->isPublic() && !$this->getUser()->isLoggedIn()) {
            $this->redirect('User:login');
        }
    }

}
