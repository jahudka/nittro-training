<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nette\SmartObject;


/**
 * @ORM\Entity()
 * @ORM\Table(name="menu_items")
 *
 * @property-read int $id
 * @property-read MenuCategory $category
 * @property-read int $position
 * @property-read string $name
 * @property-read array $price
 */
class MenuItem {
    use SmartObject;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="MenuCategory", inversedBy="items")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @var MenuCategory
     */
    private $category;

    /**
     * @ORM\Column(name="position", type="integer")
     * @var int
     */
    private $position;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="price", type="array")
     * @var array
     */
    private $price;


    public function __construct(MenuCategory $category, string $name, $price, int $position) {
        $this->category = $category;
        $this->name = $name;
        $this->setPrice($price);
        $this->position = $position;
    }

    public function getId() : int {
        return $this->id;
    }

    public function getCategory() : MenuCategory {
        return $this->category;
    }

    public function getPosition() : int {
        return $this->position;
    }

    public function getName() : string {
        return $this->name;
    }

    public function getPrice() : array {
        return $this->price;
    }

    public function setName(string $name) : void {
        $this->name = $name;
    }

    public function setPrice($price) : void {
        $this->price = array_map('intval', (array) $price);
    }

}
