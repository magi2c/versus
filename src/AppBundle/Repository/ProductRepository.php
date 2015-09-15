<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Product;
use AppBundle\Service\RouteService;
use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function getQueryBuilderByCategoryBandsAndQuery($category, $brandList, $query, $sort = null)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('p')
            ->from('AppBundle:Product', 'p')
            ->andWhere('p.status = 1')
            ->andWhere('p.locked = 0')
            ->andWhere('p.parent IS NULL');

        if (!empty($category)) {
            $queryBuilder
                ->andWhere('p.category = :category')
                ->setParameter('category', $category->getId());
        }

        if (!empty($brandList)) {
            $brandIds = array();
            foreach ($brandList as $brand) {
                $brandIds[] = $brand->getId();
            }

            if (count($brandIds) > 0) {
                $queryBuilder
                    ->andWhere('p.brand in (:brand)')
                    ->setParameter('brand', $brandIds);
            }
        }

        if (!empty($query)) {
            preg_replace('@[^\w\s-]@', '%', $query);
            $queryBuilder
                ->andWhere('p.description like :query')
                ->setParameter('query', "%".$query."%");
        }

        if (!empty($sort) && $sort = RouteService::VALUE_SORT) {
            $queryBuilder
                ->orderBy('p.salePrice', 'desc');
        } else {
            $queryBuilder
                ->addOrderBy('p.sort', 'ASC')
                ->addOrderBy('p.salePrice', 'DESC')
                ;
        }

        return $queryBuilder;
    }


    public function findFull($productId)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT pr, ca, br, sh
                 FROM AppBundle:Product as pr
                 LEFT JOIN pr.category as ca
                 LEFT JOIN pr.brand as br
                 LEFT JOIN pr.shop as sh
                 WHERE pr.id = :productId'
            )
            ->setParameter('productId', $productId)
            ->getOneOrNullResult();
    }

    public function findByParent($parent)
    {
        if (empty($parent)) {
            return null;
        }

        return $this->findByParentId($parent->getId());
    }

    public function findByParentId($parentId)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT pr, sh,
                 FROM AppBundle:Product as pr
                 LEFT JOIN pr.shop as sh
                 WHERE pr.parent = :parentId'
            )
            ->setParameter('parentId', $parentId)
            ->getResult();
    }

}
