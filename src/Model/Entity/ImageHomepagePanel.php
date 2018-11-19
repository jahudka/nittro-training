<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 *
 * @property-read int $width
 * @property-read int $height
 */
class ImageHomepagePanel extends AbstractHomepagePanel {

    /**
     * @ORM\Column(name="width", type="integer")
     * @var int
     */
    private $width;

    /**
     * @ORM\Column(name="height", type="integer")
     * @var int
     */
    private $height;


    public function __construct(int $position, int $width, int $height, ?\DateTimeImmutable $publishFrom = null, ?\DateTimeImmutable $publishUntil = null) {
        parent::__construct($position, $publishFrom, $publishUntil);
        $this->width = $width;
        $this->height = $height;
    }

    public function getWidth() : int {
        return $this->width;
    }

    public function getHeight() : int {
        return $this->height;
    }

}
