<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;

use App\Model\Manager\ContactInfoManager;
use App\PublicModule\Components\ContactControl\ContactControl;
use App\PublicModule\Components\ContactControl\IContactControlFactory;


class KontaktPresenter extends BasePresenter {

    private $contactControlFactory;

    private $contactInfoManager;


    public function __construct(IContactControlFactory $contactControlFactory, ContactInfoManager $contactInfoManager) {
        parent::__construct();
        $this->contactControlFactory = $contactControlFactory;
        $this->contactInfoManager = $contactInfoManager;
    }


    public function renderDefault() : void {
        $this->template->contactInfo = $this->contactInfoManager->getContactInfo();
    }


    public function createComponentContact() : ContactControl {
        return $this->contactControlFactory->create();
    }

}
