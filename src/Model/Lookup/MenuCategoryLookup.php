<?php

declare(strict_types=1);

namespace App\Model\Lookup;

use App\Model\Entity\MenuCategory;
use App\Model\Entity\MenuItem;
use Kdyby\Doctrine\QueryBuilder;


/**
 * @method \Generator|MenuCategory[] getIterator()
 * @method MenuCategory[] toArray()
 */
class MenuCategoryLookup extends AbstractLookup {

    public function __construct(callable $queryBuilderFactory) {
        parent::__construct($queryBuilderFactory, 'c');
    }


    public function filterBy(array $filter) : self {
        $this->addCommonModifier(function (QueryBuilder $builder) use ($filter) : void {
            $builder->whereCriteria($filter);
        });

        return $this;
    }


    public function nonEmpty() : self {
        $this->addCommonModifier(function(QueryBuilder $builder) : void {
            $builder->andWhere(sprintf(
                'c.id IN (SELECT IDENTITY(i.category) FROM %s i GROUP BY i.category)',
                MenuItem::class
            ));
        });

        return $this;
    }


    public function withItems() : self {
        $this->onLoad[] = \Closure::fromCallable([$this, 'loadItems']);
        return $this;
    }


    protected function createSelectQueryBuilder() : QueryBuilder {
        $builder = parent::createSelectQueryBuilder();
        $builder->orderBy('c.position', 'ASC');
        return $builder;
    }


    private function loadItems(array $categories) : void {
        $ids = array_column($categories, 'id');

        $this->createQueryBuilder()
            ->select('PARTIAL c.{id}, i')
            ->innerJoin('c.items', 'i')
            ->where('c.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();
    }

}
