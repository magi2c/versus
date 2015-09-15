<?php
namespace AppBundle\Component\Feed\Importer\Bike;

use AppBundle\Component\Feed\Importer\AbstractBelboomImporter;

class BikeInnImporter extends AbstractBelboomImporter
{
    protected $feedUrl  = null;

    public static $shopId      = 2;
    public static $shopKey     = 'bikeInn';
    public static $shopName    = 'bikeInn';
    public static $shopUrl     = '';

}
