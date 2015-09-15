<?php

namespace AppBundle\Controller;

use AppBundle\Component\SearchManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VerticalController extends Controller
{
    public function searchAction($categoryOrTheNext, $brandOrTheNext, $query)
    {
        $verticalName = $this->container->get('ov.vertical')->getCurrent();

        /** @var SearchManager $searchManager */
        $searchManager = $this->container->get('ov.search_manager.em_'.$verticalName);

        $searchManager->initializedByRoute($categoryOrTheNext, $brandOrTheNext, $query);

        $productPager = $searchManager->findProducts($this->get('request')->get('page', 1));

        //dump($searchManager->findShops()); die();

        return $this->render('AppBundle:Vertical:search.html.twig', array(
            'verticalName'          => $verticalName,
            'currentCategory'       => $searchManager->getCategory(),
            'currentBrandList'      => $searchManager->getBrandList(),
            'currentBrandSlugs'     => $searchManager->getBrandSlugs(),
            'currentQuery'          => $searchManager->getQuery(),
            'productPager'          => $productPager,
            'currentPageResults'    => $productPager->getCurrentPageResults(),
            'categoryList'          => $searchManager->findCategories(),
            'brandList'             => $searchManager->findBrands(),
            'shopList'              => $searchManager->findShops(),
        ));
    }

    public function productAction($key)
    {
        $verticalName = $this->container->get('ov.vertical')->getCurrent();
        $em = $this->container->get('doctrine')->getManager($verticalName);

        $productId = $this->container->get('ov.route')->entityIdByKey($key);

        $product = $em
            ->getRepository('AppBundle:Product')
            ->findFull($productId);

        $productChildren = $em
            ->getRepository('AppBundle:Product')
            ->findByParent($product->getParent());

        return $this->render('AppBundle:Vertical:product.html.twig', array(
            'verticalName'          => $verticalName,
            'product'               => $product,
            'productChildren'       => $productChildren
        ));
    }

    public function deepLinkAction($entity, $key)
    {
        $verticalName = $this->container->get('ov.vertical')->getCurrent();
        $em = $this->container->get('doctrine')->getManager($verticalName);

        $entityId = $this->container->get('ov.route')->entityIdByKey($key);

        $product = $em
            ->getRepository('AppBundle:'.ucfirst($entity))
            ->find($entityId);

        return $this->redirect($product->getUrl());
    }
}
