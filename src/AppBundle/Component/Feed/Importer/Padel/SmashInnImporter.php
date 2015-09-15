<?php
namespace AppBundle\Component\Feed\Importer\Padel;

use AppBundle\Component\Feed\Importer\AbstractBelboomImporter;

class SmashInnImporter extends AbstractBelboomImporter
{
    protected $feedUrl  = null;

    public static $shopId      = 1;
    public static $shopKey     = 'smashInn';
    public static $shopName    = 'SmashInn';
    public static $shopUrl     = null;
}
