<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\HomepagePanel\AbstractHomepagePanelForm;
use App\AdminModule\Components\HomepagePanel\ImageHomepagePanelForm;
use App\AdminModule\Components\HomepagePanel\TextHomepagePanelForm;
use App\Model\Entity\AbstractHomepagePanel;
use App\Model\Entity\TextHomepagePanel;
use App\Model\Manager\HomepagePanelManager;


class HomepagePresenter extends BasePresenter {

    private $homepagePanelManager;

    /** @var AbstractHomepagePanel */
    private $panel;


    public function __construct(HomepagePanelManager $homepagePanelManager) {
        parent::__construct();
        $this->homepagePanelManager = $homepagePanelManager;
    }

    public function actionAdd(string $type) : void {
        $this->setDefaultSnippets(['content']);
        $this->setView($type === 'text' ? '@textForm' : '@imageForm');
        $this->template->formName = $type . 'Form';
    }

    public function actionEdit(int $id) : void {
        $this->setDefaultSnippets(['content']);
        $this->template->panel = $this->panel = $this->homepagePanelManager->get($id);

        if ($this->panel instanceof TextHomepagePanel) {
            $this->setView('@textForm');
            $this->template->formName = 'textForm';

            $this->getComponent('textForm')->setDefaults([
                'content' => $this->panel->content,
                'publish_from' => $this->panel->publishFrom ? $this->panel->publishFrom->format('Y-m-d') : null,
                'publish_until' => $this->panel->publishUntil ? $this->panel->publishUntil->format('Y-m-d') : null,
            ]);
        } else {
            $this->setView('@imageForm');
            $this->template->formName = 'imageForm';

            $this->getComponent('imageForm')->setDefaults([
                'publish_from' => $this->panel->publishFrom ? $this->panel->publishFrom->format('Y-m-d') : null,
                'publish_until' => $this->panel->publishUntil ? $this->panel->publishUntil->format('Y-m-d') : null,
            ]);
        }
    }


    public function handleSaveOrder() : void {
        $order = $this->getHttpRequest()->getPost('order');
        $this->homepagePanelManager->saveOrder($order);
        $this->sendJson(null);
    }

    public function handleRemove(int $id) : void {
        $panel = $this->homepagePanelManager->get($id);
        $this->homepagePanelManager->remove($panel);
        $this->flashMessage('Panel byl smazán', 'success');
        $this->redirect('default');
    }


    public function renderDefault() : void {
        $this->template->panels = $this->homepagePanelManager->lookup();
    }


    private function savePanel(AbstractHomepagePanelForm $form, array $values) : void {
        $publishFrom = $values['publish_from'] ? new \DateTimeImmutable($values['publish_from']) : null;
        $publishUntil = $values['publish_until'] ? new \DateTimeImmutable($values['publish_until']) : null;

        if ($this->panel) {
            if ($this->panel instanceof TextHomepagePanel) {
                $this->panel->setContent($values['content']);
            }

            $this->panel->setPublishFrom($publishFrom);
            $this->panel->setPublishUntil($publishUntil);
            $this->homepagePanelManager->persist($this->panel);

            $this->flashMessage('Změny byly uloženy', 'success');
        } else {
            if ($form instanceof TextHomepagePanelForm) {
                $this->panel = $this->homepagePanelManager->createTextPanel($values['content'], $publishFrom, $publishUntil);
            } else {
                $this->panel = $this->homepagePanelManager->createImagePanel($values['image'], $publishFrom, $publishUntil);
            }

            $this->flashMessage('Panel byl přidán', 'success');
        }

        $this->postGet('default');
        $this->setView('default');
        $this->redrawControl('content');
        $this->closeDialog('editor');
        unset($this->template->panel);
    }

    private function processError() : void {
        $this->redrawControl('content');
    }

    public function createComponentTextForm() : TextHomepagePanelForm {
        $form = new TextHomepagePanelForm();
        $form->onSuccess[] = \Closure::fromCallable([$this, 'savePanel']);
        $form->onError[] = \Closure::fromCallable([$this, 'processError']);
        return $form;
    }

    public function createComponentImageForm() : ImageHomepagePanelForm {
        $form = new ImageHomepagePanelForm(isset($this->panel));
        $form->onSuccess[] = \Closure::fromCallable([$this, 'savePanel']);
        $form->onError[] = \Closure::fromCallable([$this, 'processError']);
        return $form;
    }

}
