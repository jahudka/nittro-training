<?php

declare(strict_types=1);

namespace App\Model\Lookup;

use App\Model\Entity\MenuCategory;
use App\Model\Entity\MenuItem;
use Kdyby\Doctrine\QueryBuilder;


/**
 * @method \Generator|MenuItem[] getIterator()
 * @method MenuItem[] toArray()
 */
class MenuItemLookup extends AbstractLookup {

    public function __construct(callable $queryBuilderFactory) {
        parent::__construct($queryBuilderFactory, 'c');
    }


    public function filterBy(array $filter) : self {
        $this->addCommonModifier(function (QueryBuilder $builder) use ($filter) : void {
            $builder->whereCriteria($filter);
        });

        return $this;
    }

    public function inCategory(MenuCategory $category) : self {
        $this->addCommonModifier(function(QueryBuilder $builder) use ($category) : void {
            $builder->whereCriteria(['category' => $category]);
        });

        return $this;
    }


    protected function createSelectQueryBuilder() : QueryBuilder {
        $builder = parent::createSelectQueryBuilder();
        $builder->orderBy('c.position', 'ASC');
        return $builder;
    }

}
