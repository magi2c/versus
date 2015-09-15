<?php
namespace AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RequestStack;

class SeoInfoService extends \Twig_Extension
{
    protected $varticalKey;
    protected $infoDefaultArr = array(
        'bike' => array(
            'title'         => 'Bike On Versus',
            'description'   => 'Comparador de tiendas de bicicletas',
            'keywords'      => 'comparador, precios, btt, bicicletas, mtb, bmx',
            'og_title' => 'Los mejores precios y ofertas',
            'og_description' => 'Comparador de tiendas de bicicletas',
            'og_type' => 'product',
            'og_image' => '',
            'tw_card' => 'summary',
            'tw_site' => '@bikeOnVersus',
            'tw_creator' => '@bikeOnVersus',
            'tw_image' => '',
        ),
        'padel' => array(

        ),
    );

    public function __construct(RequestStack $requestStack, VerticalService $vertical)
    {
        $this->verticalKey = $vertical->getCurrent();
        $request = $requestStack->getCurrentRequest();
        if ($request) {
            $this->infoDefaultArr[$this->verticalKey]['canonical'] = $request->getUri();
            $this->infoDefaultArr[$this->verticalKey]['og_url'] = $request->getUri();
        }
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('seo_info_set', array($this, 'set')),
            new \Twig_SimpleFunction('seo_info_get', array($this, 'get')),
        );
    }

    public function get($key)
    {
        if (array_key_exists($key, $this->infoDefaultArr[$this->verticalKey])) {

            return $this->infoDefaultArr[$this->verticalKey][$key];
        }

        return '';
    }

    public function set($key, $value)
    {
        $this->infoDefaultArr[$this->verticalKey][$key] = $value;
    }

    public function getName()
    {
        return 'app_seo_info';
    }
}