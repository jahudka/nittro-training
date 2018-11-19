<?php

declare(strict_types=1);

namespace App\AdminModule\Components\HomepagePanel;


class ImageHomepagePanelForm extends AbstractHomepagePanelForm {

    public function __construct(bool $isEdit = false) {
        parent::__construct();

        if (!$isEdit) {
            $this->addUpload('image')
                ->setAttribute('accept', '.jpg,image/jpeg')
                ->setRequired()
                ->addRule(self::IMAGE);
        }
    }

}
