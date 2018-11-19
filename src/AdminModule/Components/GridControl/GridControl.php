<?php

declare(strict_types=1);

namespace App\AdminModule\Components\GridControl;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;


class GridControl extends Control {

    private $datasourceFactory;

    private $editFormFactory;

    private $saveHandler;

    private $removeHandler;

    private $blockDefinitions = [];

    private $columns = [];

    private $actions = [
        ['label' => 'upravit', 'link' => 'edit!', 'internal' => true],
        ['label' => 'smazat', 'link' => 'remove!', 'internal' => true, 'confirm' => 'Opravdu chcete smazat tento záznam?', 'remove' => true],
    ];




    private $data = null;

    private $adding = false;

    /** @var int */
    private $editing = null;



    public function setDatasourceFactory(callable $factory) : void {
        $this->datasourceFactory = $factory;
    }

    public function setEditFormFactory(callable $factory) : void {
        $this->editFormFactory = $factory;
    }

    public function setSaveHandler(callable $handler) : void {
        $this->saveHandler = $handler;
    }

    public function setRemoveHandler(callable $handler) : void {
        $this->removeHandler = $handler;
    }

    public function addBlockDefinitions(string $path) : void {
        $this->blockDefinitions[] = $path;
    }

    public function addColumn(string $name, ?string $label = null) : void {
        $this->columns[$name] = $label ?? $name;
    }

    public function addAction(string $label, string $link, ?int $index = null) : void {
        $action = ['label' => $label, 'link' => $link];

        if ($index !== null) {
            array_splice($this->actions, $index, 0, [$action]);
        } else {
            $this->actions[] = $action;
        }
    }



    public function isAdding() : bool {
        return $this->adding;
    }

    public function isEditing() : bool {
        return isset($this->editing);
    }

    public function getEditedRowId() : int {
        return $this->editing;
    }



    private function getData() : array {
        if ($this->data === null) {
            $this->data = call_user_func($this->datasourceFactory, $this);
        }

        return $this->data;
    }



    public function render() : void {
        $this->redrawControl('imports');
        $this->template->blockDefinitions = $this->blockDefinitions;
        $this->template->columns = $this->columns;
        $this->template->actions = $this->actions;
        $this->template->adding = $this->adding;
        $this->template->editing = $this->editing;
        $this->template->data = $this->getData();
        $this->template->render(__DIR__ . '/templates/default.latte');
    }




    public function handleAdd() : void {
        $this->adding = true;

        if ($this->getPresenter()->isAjax()) {
            $this->redrawControl('footer');
            $this->redrawControl('list');
            $this->data = [];
        }
    }

    public function handleEdit(int $id) : void {
        $this->editing = $id;
        $this->redrawControl('list');
    }

    public function handleRemove(int $id) : void {
        call_user_func($this->removeHandler, $id);

        if ($this->getPresenter()->isAjax()) {
            $this->getPresenter()->sendPayload();
        } else {
            $this->redirect('this');
        }
    }

    public function handleCancel(?int $id = null) : void {
        if ($id) {
            $this->redrawControl('list');
            $this->editing = $id;
            $this->findEditedRow();
            $this->editing = null;
        } else {
            $this->redrawControl('footer');
        }
    }


    private function processEdit(Form $form, array $values) : void {
        $this->editing = !empty($values['id']) ? (int) $values['id'] : null;
        $row = call_user_func($this->saveHandler, $this->findEditedRow(), $values, $form);

        if ($this->getPresenter()->isAjax()) {
            $this->redrawControl('list');

            if (!$this->editing) {
                $this->redrawControl('footer');
            }

            $this->editing = null;
            $this->data[] = $row;
        } else {
            $this->redirect('this');
        }
    }

    private function findEditedRow() {
        if ($this->editing === null) {
            return null;
        }

        $data = $this->getData();

        foreach ($data as $row) {
            if ($row->id === $this->editing) {
                return $row;
            }
        }

        throw new \RuntimeException('Row not found');
    }

    public function createComponentEditForm() : Form {
        $form = call_user_func($this->editFormFactory, $this->findEditedRow());

        if (!isset($form['id'])) {
            $form->addHidden('id');
        }


        if (!isset($form['save'])) {
            $form->addSubmit('save', 'uložit');
        }

        $form->onSuccess[] = \Closure::fromCallable([$this, 'processEdit']);
        return $form;
    }

}
