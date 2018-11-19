<?php

declare(strict_types=1);

namespace App\AdminModule\Components\LoginControl;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;

class LoginControl extends Control {

    /** @var callable[] */
    public $onLogin = [];
    /** @var User */
    private $user;


    public function __construct(User $user) {
        parent::__construct();
        $this->user = $user;
    }


    private function doLogin(Form $form, array $credentials) : void {
        try {
            $this->user->login($credentials['login'], $credentials['password']);
            $this->user->setExpiration('+1 month');
            $this->onLogin();
        } catch (AuthenticationException $e) {
            $form->addError('Neplatné přihlašovací údaje');
            $this->redrawControl('content');
        }
    }


    public function render() : void {
        $this->template->render(__DIR__ . '/templates/default.latte');
    }


    public function createComponentForm() : Form {
        $form = new Form();
        $form->addText('login')->setRequired();
        $form->addPassword('password')->setRequired();
        $form->addSubmit('go');
        $form->addProtection();
        $form->onSuccess[] = \Closure::fromCallable([$this, 'doLogin']);
        return $form;
    }
}
