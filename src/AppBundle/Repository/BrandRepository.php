<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BrandRepository extends EntityRepository
{
    public function findListBySlug($slug)
    {
        $slugArr = explode(',', $slug);

        return $this->getEntityManager()
            ->createQuery(
                'SELECT b FROM AppBundle:Brand b WHERE b.slug in (:slugArr)'
            )
            ->setParameter('slugArr', $slugArr)
            ->getResult();
    }

    public function findCountByCategoryBandsAndQuery($category, $brandList, $query, $limit = null)
    {
        $queryBuilder = $this->_em->getRepository('AppBundle:Product')->getQueryBuilderByCategoryBandsAndQuery($category, $brandList, $query);

        $queryBuilder
            ->select('count(p.id) numProducts, b.name, b.slug')
            ->leftJoin('p.brand', 'b')
            ->andWhere('p.brand IS NOT NULL');

        $queryBuilder
            ->groupBy('b.id')
            ->orderBy('b.name');

        if ($limit) {
            $queryBuilder
                ->setMaxResults($limit)
                ->orderBy('numProducts', 'desc');
        }

        return  $queryBuilder->getQuery()->getArrayResult();
    }
}
