<?php

declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\GridControl\GridControl;
use App\Model\Entity\MenuCategory;
use App\Model\Entity\MenuItem;
use App\Model\Manager\MenuCategoryManager;
use App\Model\Manager\MenuItemManager;
use Nette\Application\UI\Form;


class MenuPresenter extends BasePresenter {

    private $menuCategoryManager;

    private $menuItemManager;


    /** @var MenuCategory */
    private $category = null;



    public function __construct(MenuCategoryManager $menuCategoryManager, MenuItemManager $menuItemManager) {
        parent::__construct();
        $this->menuCategoryManager = $menuCategoryManager;
        $this->menuItemManager = $menuItemManager;
    }


    public function actionCategory(int $id) : void {
        $this->category = $this->menuCategoryManager->get($id);
    }

    public function handleSaveOrder() : void {
        $order = $this->getHttpRequest()->getPost('order');

        if ($this->category) {
            $this->menuItemManager->saveOrder($this->category, $order);
        } else {
            $this->menuCategoryManager->saveOrder($order);
        }

        $this->sendJson([]);
    }

    public function renderCategory() : void {
        $grid = $this->getComponent('items');
        $this->template->busy = $grid->isAdding() || $grid->isEditing();
        $this->template->category = $this->category;
    }

    public function renderDefault() : void {
        $grid = $this->getComponent('categories');
        $this->template->busy = $grid->isAdding() || $grid->isEditing();
    }


    public function createComponentCategories() : GridControl {
        $grid = new GridControl();
        $grid->addColumn('name', 'Kategorie');

        $grid->setDatasourceFactory(function() : array {
            return $this->menuCategoryManager->lookup()->toArray();
        });

        $grid->setSaveHandler(function(?MenuCategory $category, array $values) {
            if ($category) {
                $this->menuCategoryManager->rename($category, $values['name']);
            } else {
                $this->menuCategoryManager->create($values['name']);
            }
        });

        $grid->setRemoveHandler(function(int $id) : void {
            $category = $this->menuCategoryManager->get((int) $id);
            $this->menuCategoryManager->remove($category);
        });

        $grid->setEditFormFactory(function(?MenuCategory $category) : Form {
            $form = new Form();
            $form->addText('name')->setRequired();
            $form->addHidden('id');

            if ($category) {
                $form->setDefaults([
                    'id' => $category->id,
                    'name' => $category->name,
                ]);
            }

            return $form;
        });

        $grid->addAction('nabídka', 'category', 0);

        return $grid;
    }


    public function createComponentItems() : GridControl {
        $grid = new GridControl();
        $grid->addBlockDefinitions(__DIR__ . '/templates/Menu/@gridblocks.latte');
        $grid->addColumn('name', 'Položka');
        $grid->addColumn('price', 'Cena');

        $grid->setDatasourceFactory(function() : array {
            return $this->menuItemManager->lookup()->inCategory($this->category)->toArray();
        });

        $grid->setSaveHandler(function(?MenuItem $item, array $values, Form $form) {
            $variants = $form->getHttpData(Form::DATA_LINE, 'variant[]');
            $prices = $form->getHttpData(Form::DATA_LINE, 'price[]');

            if (!$variants) {
                $price = $prices;
            } else {
                $idx = 0;
                $price = array_combine(
                    array_map(function($k) use (&$idx) { return $k !== '' ? $k : $idx++; }, $variants),
                    array_map('intval', $prices)
                );
            }

            $price = array_filter($price, function($price) { return !empty($price); });

            if ($item) {
                $this->menuItemManager->update($item, $values['name'], $price);
            } else {
                $this->menuItemManager->create($this->category, $values['name'], $price);
            }
        });

        $grid->setRemoveHandler(function(int $id) : void {
            $item = $this->menuItemManager->get((int) $id);
            $this->menuItemManager->remove($item);
        });

        $grid->setEditFormFactory(function(?MenuItem $item) : Form {
            $form = new Form();
            $form->addText('name')->setRequired();
            $form->addHidden('id');

            if ($item) {
                $form->setDefaults([
                    'id' => $item->id,
                    'name' => $item->name,
                ]);
            }

            return $form;
        });

        return $grid;
    }

}
