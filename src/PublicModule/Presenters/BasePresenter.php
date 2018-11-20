<?php

declare(strict_types=1);

namespace App\PublicModule\Presenters;

use Nittro\Bridges\NittroUI\Presenter;


abstract class BasePresenter extends Presenter {

    protected function startup() {
        parent::startup();

        $this->setDefaultSnippets([
            'header',
            'content',
        ]);
    }

}
