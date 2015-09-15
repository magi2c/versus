<?php
namespace AppBundle\Component\Feed\Importer\Bike;

use AppBundle\Component\Feed\Importer\AbstractEffiliationImporter;

class ProbikeShopImporter extends AbstractEffiliationImporter
{
    protected $feedUrl          = null;
    protected $feedPromosUrl    = null;
    protected $feedEanUrl       = null;

    public static $shopId      = 4;
    public static $shopKey     = 'ProbikeShop';
    public static $shopName    = 'ProbikeShop';
    public static $shopUrl     = '';
}
