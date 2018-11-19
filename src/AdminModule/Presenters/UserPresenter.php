<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\LoginControl\ILoginControlFactory;
use App\AdminModule\Components\LoginControl\LoginControl;


class UserPresenter extends BasePresenter {

    private $loginControlFactory;


    public function __construct(ILoginControlFactory $loginControlFactory) {
        parent::__construct();
        $this->loginControlFactory = $loginControlFactory;
    }


    protected function isPublic() : bool {
        return true;
    }


    public function actionLogout() : void {
        if ($this->getUser()->isLoggedIn()) {
            $this->getUser()->logout(true);
        }

        $this->redirect('login');
    }

    public function renderLogin() : void {
        if ($this->user->isLoggedIn()) {
            $this->redirect('Dashboard:');
        }
    }

    protected function afterRender() {
        $this->template->bodyClass = 'login';
    }


    public function createComponentLogin() : LoginControl {
        return $this->loginControlFactory->create();
    }

}
