<?php
namespace AppBundle\Component\Feed\Importer;

use AppBundle\Component\Feed\Importer\Adapt\BrandAdapt;
use AppBundle\Entity\Brand;
use AppBundle\Entity\CategoryShop;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

abstract class AbstractImporter
{
    public static $shopId       = null;
    public static $shopKey      = null;
    public static $shopName     = null;
    public static $shopUrl      = null;
    protected $feedUrl          = null;
    protected $feedUpdateUrl    = null;

    protected $resultArr;
    protected $logArr;

    protected $categoryList = array();
    protected $categoryShopList = array();
    protected $brandList = array();
    protected $productList = array();

    protected $em;
    protected $kernelRootDir;
    protected $slugify;

    public function __construct(EntityManagerInterface $entityManager, $slugify, $kernelRootDir)
    {
        $this->resultArr            = array('items' => 0, 'created' => 0, 'updated' => 0, 'failure' => 0, 'exception' => 0, 'deleted' => 0);
        $this->em                   = $entityManager;
        $this->kernelRootDir        = $kernelRootDir;
        $this->slugify              = $slugify;
    }

    public function import(OutputInterface $output, $oldXml = false, $updateXml = false)
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('import');
        $xml = $this->getXml($oldXml, $updateXml);
        //!!!! memory
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);

        $output->writeln('Start foreach: '.date("Y-m-d H:i:s"));
        foreach ($xml as $productXml) {
            $this->resultArr['items'] ++;
            try {
                $id = $this->createProduct($productXml);

                if (empty($id)) {
                    $this->resultArr['created'] ++;
                } else {
                    $this->resultArr['updated'] ++;
                }

            } catch (ImporterException $e ) {
                $this->logArr['failureMessage'][] = array('FailureMessage' => $e->getMessage());
                $this->resultArr['failure'] ++;
            } catch (\Exception $e ) {
                $this->logArr['errorMessage'][] = array('ErrorMessage' => $e->getMessage(),
                    'ErrorTrace'   => $e->getTraceAsString());
                $this->resultArr['exception'] ++;

                die(var_dump($this->logArr));
            }
            //$output->writeln($this->resultArr['items'].' -> mem : '.date("H:i:s").'->' . (memory_get_usage()/1024/1024));

            if (($this->resultArr['items'] % 500) == 0) {
                $this->em->flush();
                $this->clearMemory();
            }
        }

        $this->em->flush();

        $event = $stopwatch->stop('import');
        $output->writeln("\n<info>We are done! ".($event->getDuration()/60).'s '.($event->getMemory()/1024/1024)."Mb </info>");

        return array ($this->logArr, $this->resultArr);
    }

    protected function clearMemory()
    {
        $this->em->clear();
        $this->brandList = array();
        $this->productList = array();
        $this->categoryList = array();
        $this->categoryShopList = array();
    }

    public function getXml($oldXml, $updateXml = false)
    {
        if ($updateXml) {
            $fileTmp = $this->kernelRootDir . '/../data/feeds'.'/feed_update_'.$this::$shopKey.'.xml';
        } else {
            $fileTmp = $this->kernelRootDir . '/../data/feeds'.'/feed_'.$this::$shopKey.'.xml';
        }

        if ($oldXml && file_exists($fileTmp)) {
            $xml = simplexml_load_file($fileTmp);
        } else {
            $client = new Client();
            if ($updateXml) {
                $response = $client->get($this->feedUpdateUrl);
            } else {
                $response = $client->get($this->feedUrl);
            }

            //$response = $request->send();
            $xml = $response->xml();
            // guardamos xml
            $xml->asXml($fileTmp);
        }

        return $xml;
    }

    /**
     * @param Product $product
     * @param         $name
     * @param         $haystack
     */
    protected function setCategory(Product $product, $name, $haystack)
    {

        if (empty($this->categoryShopList[$name])) {
            $this->categoryShopList[$name] = $this->em->getRepository('AppBundle:CategoryShop')->findOneBy(array('name' => $name));

            if (empty($this->categoryShopList[$name])) {
                $this->categoryShopList[$name] = new CategoryShop();
                $this->categoryShopList[$name]->setName($name);
                $this->categoryShopList[$name]->setShop($this->getShop());
                $this->em->persist($this->categoryShopList[$name]);
            }
        }

        $category = $this->categoryShopList[$name]->getCategory();

        if (empty($category)) {
            $product->setStatus(0);
        } else {
            $this->recursiveSetCategory($category, $product, $haystack);
        }
    }

    protected function recursiveSetCategory($category, $product, $haystack)
    {
        $categoryChildrenList = $category->getChildren();
        if (count($categoryChildrenList) > 0) {
            foreach ($categoryChildrenList as $children) {
                if ($children->getPattern()) {
                    if (preg_match('~'.$children->getPattern().'~', $haystack)) {
                        $this->recursiveSetCategory($children, $product, $haystack);
                    }
                }
            }
        }

        if (!$product->getCategory()) {
            $product->setCategory($category);
        }
    }


    protected function getBrand($name)
    {
        $name = BrandAdapt::getOriginalName($name);

        if (empty($this->brandList[$name])) {
            $slug = $this->slugify->slugify($name);
            $brand = $this->em->getRepository('AppBundle:Brand')->findOneBy(array('slug' => $slug));

            if (empty($brand)) {
                $brand = new Brand();
                $brand->setName($name);
                $brand->setSlug($slug);
                $this->em->persist($brand);
            }

            $this->brandList[$name] = $brand;
        }

        return $this->brandList[$name];
    }


    protected function getProduct($xmlRef)
    {
        $externalRef = strtolower($this::$shopKey.'-'.$xmlRef);

        if (empty($this->productList[$externalRef])) {
            $this->productList[$externalRef] = $this->em->getRepository('AppBundle:Product')->findOneBy(array('externalRef' => $externalRef));

            if (empty($this->productList[$externalRef])) {
                $this->productList[$externalRef] = new Product();
            }

            $this->productList[$externalRef]->setShop($this->getShop());
            $this->productList[$externalRef]->setExternalRef($externalRef);
        }

        return $this->productList[$externalRef];
    }

    protected function persistProduct(Product $product)
    {
        $this->em->persist($product);

        return $product->getId();
    }

    protected function getShop()
    {
        return $this->em->getReference('AppBundle\Entity\Shop', $this::$shopId);
    }

}