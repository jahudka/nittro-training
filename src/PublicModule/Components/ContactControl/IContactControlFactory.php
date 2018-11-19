<?php

declare(strict_types=1);

namespace App\PublicModule\Components\ContactControl;


interface IContactControlFactory {

    public function create() : ContactControl;

}
