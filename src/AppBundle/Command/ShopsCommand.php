<?php
namespace AppBundle\Command;

use AppBundle\Component\Feed\Importer\Bike\AlltricksImporter;
use AppBundle\Entity\Shop;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class ShopsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setDefinition(array(
            ))
            ->setDescription('Created Shops')
            ->setHelp(<<<EOT
php app/console versus:shops
EOT
            )
            ->setName('versus:shops');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start '.date("Y-m-d H:i:s"));

        $shopClassList = array(
            'AppBundle\Component\Feed\Importer\Bike\AlltricksImporter',
            'AppBundle\Component\Feed\Importer\Bike\BikeInnImporter',
            'AppBundle\Component\Feed\Importer\Bike\ChainReactionCyclesImporter',
            'AppBundle\Component\Feed\Importer\Bike\ProbikeShopImporter',
            'AppBundle\Component\Feed\Importer\Bike\RettoImporter'
        );
        $em = $this->getContainer()->get('doctrine')->getManager('bike');
        $this->foreachShops($shopClassList, $em);
        $em->flush();



        $shopClassList = array(
            'AppBundle\Component\Feed\Importer\Padel\SmashInnImporter',
        );
        $em = $this->getContainer()->get('doctrine')->getManager('padel');
        $this->foreachShops($shopClassList, $em);
        $em->flush();


        $output->writeln('End '.date("Y-m-d H:i:s"));
    }

    protected function foreachShops($shopClassList, $em)
    {
        foreach ($shopClassList as $shopClass) {
            $shop = $em->getRepository('AppBundle:Shop')->find($shopClass::$shopId);
            if (empty($shop)) {
                $shop = new Shop();
            }
            $shop->setId($shopClass::$shopId);
            $shop->setName($shopClass::$shopName);
            $shop->setUrl($shopClass::$shopUrl);
            //$em->persist($shop);
        }
    }
}