<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\MenuCategory;
use App\Model\Entity\MenuItem;
use App\Model\Lookup\MenuItemLookup;
use Doctrine\ORM\NoResultException;
use Kdyby\Doctrine\EntityManager;


class MenuItemManager {

    private $em;

    private $items;


    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->items = $em->getRepository(MenuItem::class);
    }


    public function lookup() : MenuItemLookup {
        return new MenuItemLookup([$this->items, 'createQueryBuilder']);
    }


    public function get(int $id) : MenuItem {
        if ($item = $this->items->find($id)) {
            return $item;
        } else {
            throw new NoResultException();
        }
    }


    public function create(MenuCategory $category, string $name, $price) : MenuItem {
        $this->em->beginTransaction();

        try {
            $query = $this->em->createQuery(sprintf('SELECT MAX(i.position) o FROM %s i', MenuItem::class));
            $last = $query->getSingleScalarResult() ?? -1;

            $item = $category->addItem($name, $price, $last + 1);
            $this->em->persist($item);
            $this->em->flush();

            $this->em->commit();

            return $item;
        } catch (\Throwable $e) {
            $this->em->rollback();
            throw $e;
        }
    }

    public function update(MenuItem $item, string $name, $price) : void {
        $item->setName($name);
        $item->setPrice($price);
        $this->em->persist($item);
        $this->em->flush();
    }


    public function remove(MenuItem $item) : void {
        $this->em->remove($item);
        $this->em->flush();
    }


    public function saveOrder(MenuCategory $category, array $order) : void {
        $this->em->beginTransaction();

        try {
            $items = $this->items->findPairs(['id' => $order, 'category' => $category], 'position', 'id');
            $query = $this->em->createQuery(sprintf('UPDATE %s i SET i.position = :pos WHERE i.id = :id', MenuItem::class));

            foreach ($order as $idx => $id) {
                if (isset($items[$id]) && $items[$id] !== $idx) {
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
