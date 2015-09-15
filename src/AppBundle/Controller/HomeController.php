<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function homeAction()
    {
        $links = array(
            'bike' => $this->linksByVertical('bike'),
            'padel' => $this->linksByVertical('bike')
        );

        return $this->render('AppBundle:Home:home.html.twig',
            array(
                'links' => $links
            )
        );
    }

    protected function linksByVertical($vertical)
    {
        $em = $this->container->get('doctrine')->getManager($vertical);
        $links = array();
        $links['brandList'] = $em
            ->getRepository('AppBundle:Brand')
            ->findCountByCategoryBandsAndQuery(null, null, null, 6);

        $links['categoryList'] = $em
            ->getRepository('AppBundle:Category')
            ->findCountByCategoryBandsAndQuery(null, null, null, 6);

        $links['shopList'] = $em
            ->getRepository('AppBundle:Shop')
            ->findCountByCategoryBandsAndQuery(null, null, null, 6);

        return $links;
    }
}
