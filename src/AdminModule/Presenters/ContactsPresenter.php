<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Model\Manager\ContactInfoManager;
use Nette\Application\UI\Form;


class ContactsPresenter extends BasePresenter {

    private $contactInfoManager;


    public function __construct(ContactInfoManager $contactInfoManager) {
        parent::__construct();
        $this->contactInfoManager = $contactInfoManager;
    }


    private function saveContactInfo(Form $form, array $values) : void {
        $info = $this->contactInfoManager->getContactInfo();
        $info->import($values);
        $this->contactInfoManager->persist();

        $this->flashMessage('Změny byly uloženy', 'success');
        $this->redirect('this');
    }

    public function createComponentForm() : Form {
        $form = new Form();

        $form->addText('reservations_phone')
            ->setType('tel')
            ->setAttribute('pattern', '\\d{3} ?\\d{3} ?\\d{3}')
            ->setRequired()
            ->addRule(Form::PATTERN, 'Zadejte devět číslic', '\\d{3} ?\\d{3} ?\\d{3}');

        $form->addText('address')
            ->setRequired();

        $form->addText('company_name')
            ->setRequired();

        $form->addText('company_id')
            ->setAttribute('pattern', '\\d+')
            ->setRequired()
            ->addRule(Form::PATTERN, 'Zadejte pouze číslice', '\\d+');

        $form->addText('premises_id')
            ->setAttribute('pattern', '\\d+')
            ->setRequired()
            ->addRule(Form::PATTERN, 'Zadejte pouze číslice', '\\d+');

        $form->addText('representative_name')
            ->setRequired();

        $form->addText('representative_phone')
            ->setType('tel')
            ->setAttribute('pattern', '\\d{3} ?\\d{3} ?\\d{3}')
            ->setRequired()
            ->addRule(Form::PATTERN, 'Zadejte devět číslic', '\\d{3} ?\\d{3} ?\\d{3}');

        $form->addEmail('email')
            ->setRequired();

        $form->addSubmit('save');

        $form->onSuccess[] = \Closure::fromCallable([$this, 'saveContactInfo']);

        $form->setDefaults($this->contactInfoManager->getContactInfo()->export());

        return $form;
    }

}
