<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

class AdminController extends BaseAdminController
{
    protected function initialize(Request $request)
    {
        parent::initialize($request);
        $this->em = $this->getDoctrine()->getManager($this->entity['entity_manager']);

    }


    /**
     * @Route("/admin/", name="admin")
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }

}