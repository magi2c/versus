<?php
namespace AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

class RouteService extends \Twig_Extension
{
    const PREFIX_QUERY = 'qy-';
    const PREFIX_ID = 'id-';
    const KEY_SORT  = 'sort';
    const KEY_MAX_PAGE = 'max-p';
    const KEY_PAGE = 'page';
    const VALUE_SORT = 'price';


    protected $router;
    protected $vertical;

    public function __construct(Router $router, VerticalService $vertical)
    {
        $this->router = $router;
        $this->vertical = $vertical;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('route_product', array($this, 'product')),
            new \Twig_SimpleFunction('route_search', array($this, 'search')),
            new \Twig_SimpleFunction('route_deep_link', array($this, 'deepLink')),
        );
    }

    public function product($categorySlug, $brandSlug, $productId)
    {
        return $this->router->generate('product', array(
            'vertical'  => $this->vertical->getCurrent(),
            'category'  => $categorySlug,
            'brand'     => $brandSlug,
            'key'       => self::PREFIX_ID.$productId,
        ));
    }

    public function entityIdByKey($keyRoute)
    {
        return str_replace(self::PREFIX_ID, '', $keyRoute);
    }


    public function search($categorySlug = null, $brandSlug = null, $querySlug = null, $vertical = null)
    {
        $categoryOrTheNextSlug = null;
        $brandOrTheNextSlug = null;
        if (!empty($querySlug)) {
            $querySlug = self::PREFIX_QUERY.$querySlug;
        }

        if (!empty($categorySlug)) {
            $categoryOrTheNextSlug = $categorySlug;
        } else if (!empty($brandSlug)) {
            $categoryOrTheNextSlug = $brandSlug;
            $brandSlug = null;
        } else {
            $categoryOrTheNextSlug = $querySlug;
            $querySlug = null;
        }

        if (!empty($brandSlug)) {
            $brandOrTheNextSlug = $brandSlug;
        } else {
            $brandOrTheNextSlug = $querySlug;
            $querySlug = null;
        }

        $vertical = !empty($vertical) ? $vertical : $this->vertical->getCurrent();

        return $this->router->generate('search', array(
            'vertical'          => $vertical,
            'categoryOrTheNext' => $categoryOrTheNextSlug,
            'brandOrTheNext'    => $brandOrTheNextSlug,
            'query'             => $querySlug,
        ));
    }


    public function deepLink($entity, $entityId)
    {
        return $this->router->generate('deep_link', array(
            'vertical'  => $this->vertical->getCurrent(),
            'entity'    => $entity,
            'key'       => self::PREFIX_ID.$entityId,
        ));
    }


    public function getName()
    {
        return 'app_router';
    }
}