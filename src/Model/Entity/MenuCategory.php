<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Nette\SmartObject;


/**
 * @ORM\Entity()
 * @ORM\Table(name="menu_categories")
 *
 * @property-read int $id
 * @property-read int $position
 * @property-read string $name
 * @property-read MenuItem[] $items
 */
class MenuCategory {
    use SmartObject;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

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
     * @ORM\OneToMany(targetEntity="MenuItem", mappedBy="category")
     * @ORM\OrderBy({"position" = "ASC"})
     * @var Collection|MenuItem[]
     */
    private $items;


    public function __construct(string $name, int $position) {
        $this->name = $name;
        $this->position = $position;
        $this->items = new ArrayCollection();
    }

    public function getId() : int {
        return $this->id;
    }

    public function getPosition() : int {
        return $this->position;
    }

    public function getName() : string {
        return $this->name;
    }

    /**
     * @return MenuItem[]
     */
    public function getItems() : array {
        return $this->items->toArray();
    }



    public function setName(string $name) : void {
        $this->name = $name;
    }

    public function addItem(string $name, $price, int $position) : MenuItem {
        $item = new MenuItem($this, $name, $price, $position);
        $this->items->add($item);
        return $item;
    }

}
