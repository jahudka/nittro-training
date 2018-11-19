<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;

use App\Model\Manager\HomepagePanelManager;


class HomepagePresenter extends BasePresenter {

    private $homepagePanelManager;



    public function __construct(HomepagePanelManager $homepagePanelManager) {
        parent::__construct();
        $this->homepagePanelManager = $homepagePanelManager;
    }


    public function renderDefault() : void {
        $this->template->panels = $this->homepagePanelManager->lookup()->onlyPublic();
    }

}
