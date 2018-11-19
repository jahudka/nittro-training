<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nette\SmartObject;


/**
 * @ORM\Entity()
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Table(name="homepage_panels")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=5)
 * @ORM\DiscriminatorMap({"text" = "TextHomepagePanel", "image" = "ImageHomepagePanel"})
 *
 * @property-read int $id
 * @property-read int $position
 * @property-read \DateTimeImmutable|null $publishFrom
 * @property-read \DateTimeImmutable|null $publishUntil
 */
abstract class AbstractHomepagePanel {
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
     * @ORM\Column(name="publish_from", type="date_immutable", nullable=true)
     * @var \DateTimeImmutable
     */
    private $publishFrom;

    /**
     * @ORM\Column(name="publish_until", type="date_immutable", nullable=true)
     * @var \DateTimeImmutable
     */
    private $publishUntil;


    public function __construct(int $position, ?\DateTimeImmutable $publishFrom = null, ?\DateTimeImmutable $publishUntil = null) {
        $this->position = $position;
        $this->publishFrom = $publishFrom;
        $this->publishUntil = $publishUntil;
    }


    public function getId() : int {
        return $this->id;
    }

    public function getPosition() : int {
        return $this->position;
    }

    public function getPublishFrom() : ?\DateTimeImmutable {
        return $this->publishFrom;
    }

    public function getPublishUntil() : ?\DateTimeImmutable {
        return $this->publishUntil;
    }

    public function setPublishFrom(?\DateTimeImmutable $from) : void {
        $this->publishFrom = $from;
    }

    public function setPublishUntil(?\DateTimeImmutable $until) : void {
        $this->publishUntil = $until;
    }

}
