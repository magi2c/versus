<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


/**
 * Class FeedCommand
 *
 * @package Jht\AnuncioBundle\Command
 */
class FeedCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('feedClass', InputArgument::REQUIRED, 'Required feedClass'),
                new InputOption('oldXml', null, InputOption::VALUE_NONE, 'Down again'),
                new InputOption('updateXml', null, InputOption::VALUE_NONE, 'Down again'),
            ))
            ->setDescription('Importa Feeds')
            ->setHelp(<<<EOT
php app/console versus:feed-to-product ov.feed.bike_inn --oldXml
php app/console versus:feed-to-product ov.feed.alltricks --oldXml
php app/console versus:feed-to-product ov.feed.probikeshop --oldXml
php app/console versus:feed-to-product ov.feed.retto --oldXml
php app/console versus:feed-to-product ov.feed.chain_reaction_cycles --oldXml

php app/console versus:feed-to-product ov.feed.smash_inn --oldXml
EOT
            )
            ->setName('versus:feed-to-product');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start '.date("Y-m-d H:i:s"));

        list ($logArr, $resultArr) = $this->getContainer()->get($input->getArgument('feedClass'))->import($output, $input->getOption('oldXml'), $input->getOption('updateXml'));

        $output->writeln(print_r($resultArr, true));
        $output->writeln(print_r($logArr, true));


        $output->writeln('End '.date("Y-m-d H:i:s"));

    }
}