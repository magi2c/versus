<?php
namespace AppBundle\Test\Component;

use AppBundle\Component\SearchManager;
use AppBundle\Entity\Brand;
use AppBundle\Entity\Category;
use Mockery as m;

class SearchManagerTest extends \PHPUnit_Framework_TestCase
{
    private $object;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $repository = m::mock('Repository');
        $repository->shouldReceive('findBySlug')->withArgs(array("category_exist"))->andReturn(new Category());
        $repository->shouldReceive('findBySlug')->withArgs(array("category_or_brand_not_exist"))->andReturnNull();
        $repository->shouldReceive('findBySlug')->withArgs(array("brand_exist"))->andReturnNull();

        $repository->shouldReceive('findListBySlug')->withArgs(array("brand_exist"))->andReturn(array(new Brand()));
        $repository->shouldReceive('findListBySlug')->withArgs(array("brand_not_exist"))->andReturnNull();
        $repository->shouldReceive('findListBySlug')->withArgs(array("category_or_brand_not_exist"))->andReturnNull();


        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->with("AppBundle:Category")->andReturn($repository);
        $em->shouldReceive('getRepository')->with("AppBundle:Brand")->andReturn($repository);
        $em->shouldReceive('getRepository')->with("AppBundle:Product")->andReturn($repository);




        $this->object = new SearchManager($em);
    }

    /**
     * @test
     * @covers AppBundle\Component\SearchManager::initializedByRoute
     */
    public function shouldInitializedByRoute()
    {
        $this->object->initializedByRoute('', '', '');
        $this->assertAllRoute(null, null, null);

        $this->object->initializedByRoute('category_exist', '', '');
        $this->assertAllRoute(new Category(), null, null);

        $this->object->initializedByRoute('category_exist', 'brand_exist', '');
        $this->assertAllRoute(new Category(), array(new Brand), null);

        $this->object->initializedByRoute('category_exist', 'brand_exist', 'qy-query');
        $this->assertAllRoute(new Category(), array(new Brand), 'query');

        $this->object->initializedByRoute('brand_exist', '', '');
        $this->assertAllRoute(null, array(new Brand), null);

        $this->object->initializedByRoute('category_or_brand_not_exist', '', '');
        $this->assertAllRoute(null, null, 'category_or_brand_not_exist');

        $this->object->initializedByRoute('category_exist', 'brand_not_exist', '');
        $this->assertAllRoute(new Category(), null, 'brand_not_exist');

        $this->object->initializedByRoute('category_or_brand_not_exist', 'brand_not_exist', 'qy-query');
        $this->assertAllRoute(null, null, 'query');

        $this->object->initializedByRoute('category_exist', 'brand_not_exist', 'qy-query');
        $this->assertAllRoute(new Category(), null, 'query');

        $this->object->initializedByRoute('qy-query', '', '');
        $this->assertAllRoute(null, null, 'query');

        $this->object->initializedByRoute('category_exist', 'qy-query', '');
        $this->assertAllRoute(new Category(), null, 'query');
    }

    private function assertAllRoute($category, $brandList, $query)
    {
        $this->assertEquals($category, $this->object->getCategory());
        $this->assertEquals($brandList, $this->object->getBrandList());
        $this->assertEquals($query, $this->object->getQuery());
    }



}
