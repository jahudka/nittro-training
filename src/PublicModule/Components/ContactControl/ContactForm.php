<?php

declare(strict_types=1);

namespace App\PublicModule\Components\ContactControl;

use Nette\Application\UI\Form;


class ContactForm extends Form {

    public function __construct() {
        parent::__construct();

        $this->addEmail('email');
        $this->addTextArea('text')
            ->setRequired();
        $this->addSubmit('send');
    }

}
