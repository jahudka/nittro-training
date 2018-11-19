<?php

declare(strict_types=1);

namespace App\AdminModule\Components\HomepagePanel;


class TextHomepagePanelForm extends AbstractHomepagePanelForm {

    public function __construct() {
        parent::__construct();

        $this->addTextArea('content')
            ->setRequired();
    }

}
