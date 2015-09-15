<?php
namespace AppBundle\Test\Service;

use AppBundle\Service\VerticalService;
use Mockery as m;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RouteServiceTest extends KernelTestCase
{
    private $object;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
        $vertical = static::$kernel->getContainer()->get('ov.vertical');
        $vertical->setCurrent(VerticalService::BIKE);

        $this->object = static::$kernel->getContainer()->get('ov.route');
    }

    /**
     * @test
     * @covers AppBundle\Service\RouterService:product
     */
    public function shouldGetUrlProduct()
    {
        $this->assertEquals('/bike/doble-27/commencal/producto-1234', $this->object->product('doble-27', 'commencal', '1234'));
    }

    /**
     * @test
     * @covers AppBundle\Service\RouterService:search
     */
    public function shouldGetUrlSearch()
    {
        $this->assertEquals('/bike', $this->object->search());
        $this->assertEquals('/bike/doble-27', $this->object->search('doble-27'));
        $this->assertEquals('/bike/commencal', $this->object->search(null, 'commencal'));
        $this->assertEquals('/bike/qy-bicis-baratas', $this->object->search(null, null, 'bicis-baratas'));
    }

    /**
     * @test
     * @covers AppBundle\Service\RouterService:deepLink
     */
    public function shouldGetUrlDeepLink()
    {
        $this->assertEquals('/bike/product/deep-link/id-14', $this->object->deepLinkProduct(14));
    }

}
