<?php
namespace AppBundle\Component;


use AppBundle\Service\RouteService;
use Doctrine\ORM\EntityManager;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class SearchManager
{
    private $categoryRepository;
    private $brandRepository;
    private $shopRepository;
    private $productRepository;
    private $category;
    private $brandList;
    private $query;
    private $paramsArr = array();

    public function __construct(EntityManager $em)
    {
        $this->categoryRepository = $em->getRepository('AppBundle:Category');
        $this->brandRepository = $em->getRepository('AppBundle:Brand');
        $this->shopRepository = $em->getRepository('AppBundle:Shop');
        $this->productRepository = $em->getRepository('AppBundle:Product');

        $this->paramsArr[RouteService::KEY_PAGE] = 1;
        $this->paramsArr[RouteService::KEY_MAX_PAGE] = 30;
        $this->paramsArr[RouteService::KEY_SORT] = null;
    }

    public function initializedByRoute($categoryOrTheNext, $brandOrTheNext, $query)
    {
        $this->category = null;
        $this->brandList = array();
        $this->query = null;

        if (!empty($categoryOrTheNext)) {
            if (preg_match("/^".RouteService::PREFIX_QUERY.".+/", $categoryOrTheNext)) {
                $this->query = str_replace(RouteService::PREFIX_QUERY, '', $categoryOrTheNext);
            } else {
                $this->category = $this->categoryRepository->findBySlug($categoryOrTheNext);
                if (empty($this->category)) {
                    $this->brandList = $this->brandRepository->findListBySlug($categoryOrTheNext);
                    if (empty($this->brandList)) {
                        $this->query = $categoryOrTheNext;
                    }
                }
            }
        }

        if (!empty($brandOrTheNext)) {
            if (preg_match("/^".RouteService::PREFIX_QUERY.".+/", $brandOrTheNext)) {
                $this->query = str_replace(RouteService::PREFIX_QUERY, '', $brandOrTheNext);
            } else {
                $this->brandList = $this->brandRepository->findListBySlug($brandOrTheNext);
                if (empty($this->brandList)) {
                    $this->query = $brandOrTheNext;
                }
            }
        }

        if (!empty($query)) {
            $this->query = str_replace(RouteService::PREFIX_QUERY, '', $query);
        }
    }

    public function setParams($paramsArr)
    {
        if (!empty($paramsArr[RouteService::KEY_PAGE])) {
            $this->paramsArr[RouteService::KEY_PAGE] = $paramsArr[RouteService::KEY_PAGE];
        }

        if (!empty($paramsArr[RouteService::KEY_MAX_PAGE])) {
            $this->paramsArr[RouteService::KEY_MAX_PAGE] = $paramsArr[RouteService::KEY_MAX_PAGE];
        }

        if (!empty($paramsArr[RouteService::KEY_SORT])) {
            $this->paramsArr[RouteService::KEY_SORT] = $paramsArr[RouteService::KEY_SORT];
        }
    }



    public function findProducts($page = null)
    {
        if (!empty($page)) {
            $this->paramsArr[RouteService::KEY_PAGE] = $page;
        }

        $queryBuilder = $this
            ->productRepository
            ->getQueryBuilderByCategoryBandsAndQuery(
                $this->category,
                $this->brandList,
                $this->query,
                $this->paramsArr[RouteService::KEY_SORT]
            );

        $adapter = new DoctrineORMAdapter($queryBuilder);

        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($this->paramsArr[RouteService::KEY_MAX_PAGE]);
        $pagerfanta->setCurrentPage($this->paramsArr[RouteService::KEY_PAGE]);

        return $pagerfanta;
    }

    public function findBrands()
    {
        return $this->brandRepository->findCountByCategoryBandsAndQuery($this->category, $this->brandList, $this->query, 100);
    }

    public function findCategories()
    {
        return $this->categoryRepository->findCountByCategoryBandsAndQuery($this->category, $this->brandList, $this->query);
    }

    public function findShops()
    {
        return $this->shopRepository->findCountByCategoryBandsAndQuery($this->category, $this->brandList, $this->query);
    }

    /**
     * @return mixed
     */
    public function getBrandList()
    {
        return $this->brandList;
    }

    /**
     * @return string
     */
    public function getBrandSlugs()
    {
        $slugArr = array();

        foreach ($this->brandList as $brand) {

            $slugArr[] = $brand->getSlug();
        }

        return implode(',', $slugArr);
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }


}