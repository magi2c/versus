<?php
namespace AppBundle\Command;

use AppBundle\Component\Feed\Importer\Bike\AlltricksImporter;
use AppBundle\Entity\Category;
use AppBundle\Entity\Shop;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Yaml\Parser;


class CategoryCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setDefinition(array(
            ))
            ->setDescription('Created Category')
            ->setHelp(<<<EOT
php app/console versus:category
EOT
            )
            ->setName('versus:category');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start '.date("Y-m-d H:i:s"));
//Todo Connection

        $yaml = new Parser();
        $categoriesArr = $yaml->parse(file_get_contents($this->getContainer()->get('kernel')->getRootDir().'/../data/category_bike.yml'));
        $output->writeln(dump($categoriesArr));

        $order = 1;
        $this->recursiveCreate($categoriesArr, null, $order);

        $output->writeln('End '.date("Y-m-d H:i:s"));
    }


    protected function recursiveCreate($categoriesArr, $parent, &$order)
    {
        //TODO parameter
        $em = $this->getContainer()->get('doctrine')->getManager('bike');

        foreach ($categoriesArr as $categoryName => $categoryChildren) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setStatus(1);
            $category->setOrder($order);
            $parenName = '';
            if ($parent) {
                $category->setParent($parent);
                $parenName = $parent->getName();
                if ($parent->getParent()) {
                    $parenName = $parent->getParent()->getName().' '. $parenName;
                }
            }

            $category->setSlug($this
                    ->getContainer()
                    ->get('cocur_slugify')
                    ->slugify($parenName.' '.$categoryName)
            );

            $order++;
            $em->persist($category);
            if (!is_array($categoryChildren)) {
                $category->setPattern($categoryChildren);
            } else {
                if (array_key_exists('_pattern', $categoryChildren)) {
                    $category->setPattern($categoryChildren['_pattern']);
                    $this->recursiveCreate($categoryChildren['_children'], $category, $order);
                } else {
                    $this->recursiveCreate($categoryChildren, $category, $order);
                }
            }
        }

        $em->flush();
    }
}