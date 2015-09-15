<?php
namespace AppBundle\Component\Feed\Importer;



class AbstractBelboomImporter extends AbstractImporter
{
    /**
     * belboonproductnumber / belboonprogramid
     * productnumber
     * ean
     * productname
     * manufacturername.brandname
     * currentprice
     * oldprice
     * currency / validfrom / validuntil
     * deeplinkurl
     * imagesmallurl / imagesmallwidth / imagebigurl / imagebigwidth
     * productcategory
     * productkeywords
     * productdescriptionshort / productdescriptionslong
     * lastupdate
     * shipping
     * availability
     * option1 / option2 / option3 / option4 / option5
     *
     * @param $productXml
     * @return Product
     */
    protected function createProduct($productXml)
    {
        $xmlRef = (string) $productXml->option1;
        $xmlRef = empty($xmlRef) ? (string) $productXml->belboonproductnumber : $xmlRef;
        $product = $this->getProduct($xmlRef);

        $product->setUpc((string) $productXml->option2);
        $product->setIsbn((string) $productXml->productnumber);
        $product->setEan((string) $productXml->ean);

        $product->setTitle((string) $productXml->productname);
        $product->setDescription((string) $productXml->productdescriptionslong);
        $product->setSummary((string) $productXml->productdescriptionshort);
        $product->setTags('');


        $product->setSaleprice((float) $productXml->currentprice);
        $product->setOriginalprice((float) $productXml->oldprice);
        $product->setUrl((string) $productXml->deeplinkurl);


        $product->setImageSmall((string) $productXml->imagesmallurl);
        $product->setImageBig((string) $productXml->imagebigurl);

        $product->setBrand($this->getBrand((string) $productXml->brandname));


        $this->setCategory(
            $product,
            (string) $productXml->productcategory,
            (string) $productXml->productdescriptionslong
        );

        /**
         * TODO
         *
         * productcategory
         * optionX
         */

//        dump($productXml);
//        dump($product);
//        die();


        return $this->persistProduct($product);
    }


}
