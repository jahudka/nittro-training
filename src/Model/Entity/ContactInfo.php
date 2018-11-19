<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Nette\SmartObject;


/**
 * @property-read string $reservationsPhone
 * @property-read string $address
 * @property-read string $companyName
 * @property-read string $companyId
 * @property-read string $premisesId
 * @property-read string $representativeName
 * @property-read string $representativePhone
 * @property-read string $email
 */
class ContactInfo implements \JsonSerializable {
    use SmartObject;
    
    private const MAP = [
        'reservations_phone' => 'reservationsPhone',
        'address' => 'address',
        'company_name' => 'companyName',
        'company_id' => 'companyId',
        'premises_id' => 'premisesId',
        'representative_name' => 'representativeName',
        'representative_phone' => 'representativePhone',
        'email' => 'email',
    ];
    

    private $reservationsPhone;

    private $address;

    private $companyName;

    private $companyId;

    private $premisesId;

    private $representativeName;

    private $representativePhone;

    private $email;
    
    
    
    public function __construct(array $data = []) {
        $this->applyData($data);
    }

    public function getReservationsPhone() : string {
        return $this->reservationsPhone;
    }

    public function getAddress() : string {
        return $this->address;
    }

    public function getCompanyName() : string {
        return $this->companyName;
    }

    public function getCompanyId() : string {
        return $this->companyId;
    }

    public function getPremisesId() : string {
        return $this->premisesId;
    }

    public function getRepresentativeName() : string {
        return $this->representativeName;
    }

    public function getRepresentativePhone() : string {
        return $this->representativePhone;
    }

    public function getEmail() : string {
        return $this->email;
    }


    public function import(array $data) : void {
        if ($missing = array_diff_key(self::MAP, $data)) {
            throw $this->invalidData('missing', $missing);
        } else if ($unknown = array_diff_key($data, self::MAP)) {
            throw $this->invalidData('unknown', $unknown);
        }

        $this->applyData($data);
    }

    public function export() : array {
        $data = [];

        foreach (self::MAP as $key => $prop) {
            $data[$key] = $this->{$prop};
        }

        return $data;
    }

    public function jsonSerialize() : array {
        return $this->export();
    }

    private function applyData(array $data) : void {
        foreach ($data as $k => $v) {
            $this->{self::MAP[$k]} = $v;
        }
    }

    private function invalidData(string $type, array $props) : \InvalidArgumentException {
        return new \InvalidArgumentException(sprintf(
            'Invalid contact data, %s %s "%s"',
            $type,
            count($props) > 1 ? 'properties' : 'property',
            implode('", "', array_keys($props))
        ));
    }

}
