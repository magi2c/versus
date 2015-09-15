<?php
namespace AppBundle\Test\Service;

use AppBundle\Service\VerticalService;
use Mockery as m;

class VerticalServiceTest extends \PHPUnit_Framework_TestCase
{
    private $object;


    /**
     * @test
     * @covers AppBundle\Service\RouterService:getCurrent
     */
    public function shouldGetWithoutRequest()
    {
        $requestStack = m::mock('Symfony\Component\HttpFoundation\RequestStack');
        $requestStack->shouldReceive('getCurrentRequest')->andReturnNull();

        $this->object = new VerticalService($requestStack);
        $this->assertNull($this->object->getCurrent());
    }

    /**
     * @test
     * @covers AppBundle\Service\RouterService:getCurrent
     */
    public function shouldGetFromTheSet()
    {
        $requestStack = m::mock('Symfony\Component\HttpFoundation\RequestStack');
        $requestStack->shouldReceive('getCurrentRequest')->andReturnNull();

        $this->object = new VerticalService($requestStack);
        $this->object->setCurrent(VerticalService::BIKE);
        $this->assertEquals('bike', $this->object->getCurrent());
    }

    protected function getVerticalServiceBy($uri)
    {
        $request = m::mock('Request');
        $requestStack = m::mock('Symfony\Component\HttpFoundation\RequestStack');
        $request->shouldReceive('getPathInfo')->andReturn($uri);
        $requestStack->shouldReceive('getCurrentRequest')->andReturn($request);


        return new VerticalService($requestStack);
    }

    /**
     * @test
     * @covers AppBundle\Service\RouterService:getCurrent
     */
    public function shouldGetFromTheUrl()
    {
        $this->object = $this->getVerticalServiceBy('/bike/');
        $this->assertEquals('bike', $this->object->getCurrent());

        $this->object = $this->getVerticalServiceBy('/bike/bicis');
        $this->assertEquals('bike', $this->object->getCurrent());

        $this->object = $this->getVerticalServiceBy('/bike?query=ss/');
        $this->assertEquals('bike', $this->object->getCurrent());

        $this->object = $this->getVerticalServiceBy('/padel/raquetas/padelator/');
        $this->assertEquals('padel', $this->object->getCurrent());

        $this->object = $this->getVerticalServiceBy('');
        $this->assertNull($this->object->getCurrent());
    }

}
