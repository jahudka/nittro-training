<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\AbstractHomepagePanel;
use App\Model\Entity\ImageHomepagePanel;
use App\Model\Entity\TextHomepagePanel;
use App\Model\Lookup\HomepagePanelLookup;
use Doctrine\ORM\NoResultException;
use Kdyby\Doctrine\EntityManager;
use Nette\Http\FileUpload;
use Nette\Utils\Image;


class HomepagePanelManager {

    private $em;

    private $panels;

    private $storagePath;


    public function __construct(EntityManager $em, string $storagePath) {
        $this->em = $em;
        $this->panels = $em->getRepository(AbstractHomepagePanel::class);
        $this->storagePath = $storagePath;
    }


    public function lookup() : HomepagePanelLookup {
        return new HomepagePanelLookup([$this->panels, 'createQueryBuilder']);
    }

    public function get(int $id) : AbstractHomepagePanel {
        if ($panel = $this->panels->find($id)) {
            return $panel;
        } else {
            throw new NoResultException();
        }
    }

    public function createTextPanel(string $content, ?\DateTimeImmutable $publishFrom = null, ?\DateTimeImmutable $publishUntil = null) : TextHomepagePanel {
        $this->em->beginTransaction();

        try {
            $query = $this->em->createQuery(sprintf('SELECT MAX(p.position) o FROM %s p', AbstractHomepagePanel::class));
            $last = $query->getSingleScalarResult() ?? -1;

            $panel = new TextHomepagePanel($last + 1, $content, $publishFrom, $publishUntil);
            $this->em->persist($panel);
            $this->em->flush();

            $this->em->commit();

            return $panel;
        } catch (\Throwable $e) {
            $this->em->rollback();
            throw $e;
        }
    }

    public function createImagePanel(FileUpload $image, ?\DateTimeImmutable $publishFrom = null, ?\DateTimeImmutable $publishUntil = null) : ImageHomepagePanel {
        $this->em->beginTransaction();

        try {
            $query = $this->em->createQuery(sprintf('SELECT MAX(p.position) o FROM %s p', AbstractHomepagePanel::class));
            $last = $query->getSingleScalarResult() ?? -1;

            [$width, $height] = $image->getImageSize();
            $panel = new ImageHomepagePanel($last + 1, $width, $height, $publishFrom, $publishUntil);

            $this->em->persist($panel);
            $this->em->flush();

            $image->move(sprintf('%s/%s.full.jpg', $this->storagePath, $panel->getId()));

            $im = $image->toImage();
            $im->resize(510, 340, Image::EXACT);
            $im->save(sprintf('%s/%s.sm.jpg', $this->storagePath, $panel->getId()));

            $this->em->commit();

            return $panel;
        } catch (\Throwable $e) {
            $this->em->rollback();
            throw $e;
        }
    }

    public function persist(AbstractHomepagePanel $panel) : void {
        $this->em->persist($panel);
        $this->em->flush();
    }

    public function remove(AbstractHomepagePanel $panel) : void {
        if ($panel instanceof ImageHomepagePanel) {
            @unlink(sprintf('%s/%s.full.jpg', $this->storagePath, $panel->getId()));
            @unlink(sprintf('%s/%s.sm.jpg', $this->storagePath, $panel->getId()));
        }

        $this->em->remove($panel);
        $this->em->flush();
    }

    public function saveOrder(array $order) : void {
        $this->em->beginTransaction();

        try {
            $panels = $this->panels->findPairs(['id' => $order], 'position', 'id');
            $query = $this->em->createQuery(sprintf('UPDATE %s p SET p.position = :pos WHERE p.id = :id', AbstractHomepagePanel::class));

            foreach ($order as $idx => $id) {
                if (isset($panels[$id]) && $panels[$id] !== $idx) {
                    $query->execute(['id' => $id, 'pos' => $idx]);
                }
            }

            $this->em->commit();
        } catch (\Throwable $e) {
            $this->em->rollback();
            throw $e;
        }
    }

}
