<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\ContactInfo;


class ContactInfoManager {

    private $configPath;

    private $info;


    public function __construct(string $configPath) {
        $this->configPath = $configPath;
    }


    public function getContactInfo() : ContactInfo {
        if ($this->info) {
            return $this->info;
        }

        if ($data = @file_get_contents($this->configPath)) {
            $data = json_decode($data, true);
        }

        return $this->info = new ContactInfo($data ?: null);
    }

    public function persist() : void {
        if ($this->info) {
            @mkdir(dirname($this->configPath), 0755, true);
            file_put_contents($this->configPath, json_encode($this->info, JSON_PRETTY_PRINT));
        }
    }

}
