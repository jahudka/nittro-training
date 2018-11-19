<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 *
 * @property-read string $content
 */
class TextHomepagePanel extends AbstractHomepagePanel {

    /**
     * @ORM\Column(name="content", type="text")
     * @var string
     */
    private $content;


    public function __construct(int $position, string $content, ?\DateTimeImmutable $publishFrom = null, ?\DateTimeImmutable $publishUntil = null) {
        parent::__construct($position, $publishFrom, $publishUntil);
        $this->content = $content;
    }


    public function getContent() : string {
        return $this->content;
    }

    public function setContent(string $content) : void {
        $this->content = $content;
    }

}
