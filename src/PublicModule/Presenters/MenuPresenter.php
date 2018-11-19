<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;

use App\Model\Manager\MenuCategoryManager;


class MenuPresenter extends BasePresenter {

    private $categoryManager;


    public function __construct(MenuCategoryManager $categoryManager) {
        parent::__construct();
        $this->categoryManager = $categoryManager;
    }


    public function renderDefault() : void {
        $this->template->categories = $this->categoryManager->lookup()
            ->nonEmpty()
            ->withItems();
    }

}
