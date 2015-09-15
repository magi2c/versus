<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ShopRepository extends EntityRepository
{
    public function findCountByCategoryBandsAndQuery($category, $brandList, $query, $limit = null)
    {
        $queryBuilder = $this->_em->getRepository('AppBundle:Product')->getQueryBuilderByCategoryBandsAndQuery($category, $brandList, $query);

        $queryBuilder
            ->select('count(p.id) numProducts, s.name, s.id, s.url')
            ->leftJoin('p.shop', 's')
            ->andWhere('p.shop IS NOT NULL');

        $queryBuilder
            ->groupBy('s.id')
            ->orderBy('s.name');

        return  $queryBuilder->getQuery()->getArrayResult();
    }
}
