<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findBySlug($slug)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT c FROM AppBundle:Category c WHERE c.slug = :slug'
            )
            ->setParameter('slug', $slug)
            ->getOneOrNullResult();
    }


    public function findCountByCategoryBandsAndQuery($category, $brandList, $query, $limit = null)
    {
        return $this->findAllParents();

        $queryBuilder = $this->_em->getRepository('AppBundle:Product')->getQueryBuilderByCategoryBandsAndQuery($category, $brandList, $query);

        $queryBuilder
            ->select('count(p.id) numProducts, c.name, c.slug')
            ->leftJoin('p.category', 'c')
            ->andWhere('p.category IS NOT NULL')
        ;

        $queryBuilder
            ->groupBy('p.id');


        if ($limit) {
            $queryBuilder
                ->setMaxResults($limit)
                ->orderBy('numProducts', 'desc');
        }

        return  $queryBuilder->getQuery()->getArrayResult();
    }

    public function findAllParents()
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('c')
            ->from('AppBundle:Category', 'c')
            ->where('c.parent IS NULL')
            ->orderBy('c.order');

        return  $queryBuilder->getQuery()->getResult();
    }

}
