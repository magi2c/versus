<?php
namespace AppBundle\Component\Feed\Importer;


class AbstractEffiliationImporter extends AbstractImporter
{


    /**
     *
    +"sku": SimpleXMLElement {#398}
    +"name": SimpleXMLElement {#392}
    +"url_product": SimpleXMLElement {#393}
    +"url_image": SimpleXMLElement {#397}
    +"description": SimpleXMLElement {#396}
    +"merchant_store_name": SimpleXMLElement {#394}
    +"price": SimpleXMLElement {#395}
    +"availability": SimpleXMLElement {#388}
    +"ean": SimpleXMLElement {#386}
    +"upc": SimpleXMLElement {#401}
    +"isbn": SimpleXMLElement {#404}
    +"weight": SimpleXMLElement {#403}
    +"size": SimpleXMLElement {#406}
    +"brand": SimpleXMLElement {#409}
    +"short_description": SimpleXMLElement {#413}
    +"merchant_univers_name": SimpleXMLElement {#408}
    +"merchant_category_name": SimpleXMLElement {#414}
    +"merchant_department_name": SimpleXMLElement {#407}
    +"currency": SimpleXMLElement {#410}
    +"ecotax": SimpleXMLElement {#415}
    +"warranty": SimpleXMLElement {#416}
    +"in_stock": SimpleXMLElement {#417}
    +"shipping_cost": SimpleXMLElement {#418}
    +"delivery_time": SimpleXMLElement {#419}
    +"delivery_detail": SimpleXMLElement {#420}
    +"promo": SimpleXMLElement {#421}
    +"pricenorebate": SimpleXMLElement {#422}
    +"percentagepromo": SimpleXMLElement {#423}
    +"promostart": SimpleXMLElement {#424}
    +"promoend": SimpleXMLElement {#425}
    +"used": SimpleXMLElement {#426}
    +"extras": SimpleXMLElement {#427
        +"gtin": SimpleXMLElement {#460}
        +"mpn": SimpleXMLElement {#461}
        +"google_product_category": SimpleXMLElement {#462}
     * @param $productXml
     * @return Product
     */
    protected function createProduct($productXml)
    {
//        dump($productXml);
//        dump((string) $productXml->merchant_univers_name);
//        dump((string) $productXml->merchant_category_name);
//        dump((string) $productXml->merchant_store_name);
//        die();

        $product = $this->getProduct((string) $productXml->sku);

        $product->setEan((string) $productXml->ean);

        $product->setTitle((string) $productXml->name);
        $product->setDescription((string) $productXml->description);
        $product->setSummary((string) $productXml->short_description);
        $product->setTags('');


        $product->setSaleprice((float) $productXml->price);
        $product->setOriginalprice((float) $productXml->pricenorebate);
        $product->setUrl((string) $productXml->url_product);


        $product->setImageSmall((string) $productXml->url_image);
        $product->setImageBig((string) $productXml->url_image);

        $product->setBrand($this->getBrand((string) $productXml->brand));


        $this->setCategory(
            $product,
            (string) $productXml->merchant_store_name.' / '.(string) $productXml->merchant_category_name,
            (string) $productXml->description
        );

        /**
         * TODO
         *
         * Molts...
         */

//        dump($productXml);
//        dump($product);
//        die();

        $this->em->persist($product);

        return $this->persistProduct($product);
    }


}
