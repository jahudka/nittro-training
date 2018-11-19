<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\MenuCategory;
use App\Model\Lookup\MenuCategoryLookup;
use Doctrine\ORM\NoResultException;
use Kdyby\Doctrine\EntityManager;


class MenuCategoryManager {

    private $em;

    private $categories;


    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->categories = $em->getRepository(MenuCategory::class);
    }


    public function lookup() : MenuCategoryLookup {
        return new MenuCategoryLookup([$this->categories, 'createQueryBuilder']);
    }


    public function get(int $id) : MenuCategory {
        if ($category = $this->categories->find($id)) {
            return $category;
        } else {
            throw new NoResultException();
        }
    }


    public function create(string $name) : MenuCategory {
        $this->em->beginTransaction();

        try {
            $query = $this->em->createQuery(sprintf('SELECT MAX(c.position) o FROM %s c', MenuCategory::class));
            $last = $query->getSingleScalarResult() ?? -1;

            $panel = new MenuCategory($name, $last + 1);
            $this->em->persist($panel);
            $this->em->flush();

            $this->em->commit();

            return $panel;
        } catch (\Throwable $e) {
            $this->em->rollback();
            throw $e;
        }
    }

    public function rename(MenuCategory $category, string $name) : void {
        $category->setName($name);
        $this->em->persist($category);
        $this->em->flush();
    }


    public function remove(MenuCategory $category) : void {
        $this->em->remove($category);
        $this->em->flush();
    }


    public function saveOrder(array $order) : void {
        $this->em->beginTransaction();

        try {
            $categories = $this->categories->findPairs(['id' => $order], 'position', 'id');
            $query = $this->em->createQuery(sprintf('UPDATE %s c SET c.position = :pos WHERE c.id = :id', MenuCategory::class));

            foreach ($order as $idx => $id) {
                if (isset($categories[$id]) && $categories[$id] !== $idx) {
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
