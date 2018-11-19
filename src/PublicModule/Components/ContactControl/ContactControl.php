<?php

declare(strict_types=1);

namespace App\PublicModule\Components\ContactControl;

use App\Model\Manager\ContactInfoManager;
use Nette\Application\UI\Control;
use Nette\Http\IRequest;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Nittro\Bridges\NittroUI\ComponentUtils;


class ContactControl extends Control {
    use ComponentUtils;

    private $mailer;

    private $contactInfoManager;

    private $sent = false;


    public function __construct(IMailer $mailer, IRequest $httpRequest, ContactInfoManager $contactInfoManager) {
        parent::__construct();
        $this->mailer = $mailer;
        $this->contactInfoManager = $contactInfoManager;
        $this->sent = (bool) $httpRequest->getQuery('sent');
    }


    public function render() : void {
        $this->template->render(sprintf('%s/templates/%s.latte', __DIR__, $this->sent ? 'sent' : 'default'));
    }


    private function sendMessage(ContactForm $form, array $values) : void {
        $tpl = $this->createTemplate();
        $tpl->text = $values['text'];
        $tpl->email = $values['email'];
        $tpl->setFile(__DIR__ . '/templates/mail.latte');

        $msg = new Message();
        $msg->addTo($this->contactInfoManager->getContactInfo()->getEmail());
        $msg->setHtmlBody($tpl);

        if ($values['email']) {
            $msg->addReplyTo($values['email']);
        }

        $this->mailer->send($msg);

        if (!$this->getPresenter()->isAjax()) {
            $this->redirect('this', ['sent' => true]);
        }

        $this->postGet('this');
        $this->redrawControl('content');
        $this->sent = true;
    }

    public function createComponentForm() : ContactForm {
        $form = new ContactForm();
        $form->onSuccess[] = \Closure::fromCallable([$this, 'sendMessage']);
        return $form;
    }

}
