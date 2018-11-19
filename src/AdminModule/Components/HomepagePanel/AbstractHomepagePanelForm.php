<?php

declare(strict_types=1);

namespace App\AdminModule\Components\HomepagePanel;

use Nette\Application\UI\Form;


abstract class AbstractHomepagePanelForm extends Form {

    public function __construct() {
        parent::__construct();

        $this->addText('publish_from')
            ->setType('date')
            ->addCondition(self::FILLED)
            ->addRule(self::PATTERN, 'Zadejte, prosím, datum ve formátu RRRR-MM-DD', '\\d\\d\\d\\d-\\d\\d-\\d\\d');

        $this->addText('publish_until')
            ->setType('date')
            ->addCondition(self::FILLED)
            ->addRule(self::PATTERN, 'Zadejte, prosím, datum ve formátu RRRR-MM-DD', '\\d\\d\\d\\d-\\d\\d-\\d\\d');

        $this->addSubmit('save');
    }

}
