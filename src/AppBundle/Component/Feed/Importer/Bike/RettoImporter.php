<?php
namespace AppBundle\Component\Feed\Importer\Bike;

use AppBundle\Component\Feed\Importer\AbstractBelboomImporter;

class RettoImporter extends AbstractBelboomImporter
{
    protected $feedUrl  = null;

    public static $shopId      = 5;
    public static $shopKey     = 'Retto';
    public static $shopName    = 'Retto';
    public static $shopUrl     = '';
}
