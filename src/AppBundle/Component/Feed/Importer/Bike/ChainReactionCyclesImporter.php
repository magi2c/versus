<?php
namespace AppBundle\Component\Feed\Importer\Bike;

use AppBundle\Component\Feed\Importer\AbstractZanoxImporter;

class ChainReactionCyclesImporter extends AbstractZanoxImporter
{
    protected $feedUrl  = null;
    //GetUpdateDate
    protected $feedUpdateUrl  = null;

    public static $shopId      = 3;
    public static $shopKey     = 'ChainReactionCycles';
    public static $shopName    = 'ChainReactionCycles';
    public static $shopUrl     = '';
}
