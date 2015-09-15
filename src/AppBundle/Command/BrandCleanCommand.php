<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class BrandCleanCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputOption('force', null, InputOption::VALUE_NONE, 'force'),
            ))
            ->setDescription('Importa Feeds')
            ->setHelp(<<<EOT
php app/console versus:brand-clean
php app/console versus:brand-clean --force
EOT
            )
            ->setName('versus:brand-clean');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start '.date("Y-m-d H:i:s"));

        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $output->writeln('TODOoooo');
        //$em->getRepository('AppBundle:Brand')->
        //list ($logArr, $resultArr) = $this->getContainer()->get($input->getArgument('feedClass'))->import($output, $input->getOption('oldXml'));



        $output->writeln('End '.date("Y-m-d H:i:s"));

    }
}