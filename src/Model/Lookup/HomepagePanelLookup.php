<?php

declare(strict_types=1);

namespace App\Model\Lookup;

use App\Model\Entity\AbstractHomepagePanel;
use Kdyby\Doctrine\QueryBuilder;


/**
 * @method \Generator|AbstractHomepagePanel[] getIterator()
 * @method AbstractHomepagePanel[] toArray()
 */

class HomepagePanelLookup extends AbstractLookup {

    public function __construct(callable $queryBuilderFactory) {
        parent::__construct($queryBuilderFactory, 'p');
    }


    public function onlyPublic() : self {
        $this->addCommonModifier(function(QueryBuilder $builder) : void {
            $builder->andWhere('p.publishFrom IS NULL OR p.publishFrom <= :now');
            $builder->andWhere('p.publishUntil IS NULL OR p.publishUntil >= :now');
            $builder->setParameter('now', new \DateTimeImmutable());
        });

        return $this;
    }


    protected function createSelectQueryBuilder() : QueryBuilder {
        $builder = parent::createSelectQueryBuilder();
        $builder->orderBy('p.position', 'ASC');
        return $builder;
    }

}
