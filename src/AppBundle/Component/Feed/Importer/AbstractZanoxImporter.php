<?php
namespace AppBundle\Component\Feed\Importer;



use AppBundle\Entity\Product;


class AbstractZanoxImporter extends AbstractImporter
{
    /**
    +"@attributes": array:1 ["zupid" => "89ea08cbfa34bfbef240505bcd916cae"]
    +"name": "Cubierta Maxxis Crossmark LUST"
    +"number": "100003ES"
    +"deepLink": "http://ad.zanox.com/ppc/?31250791C1673470364&ULP=[[fm-d0156/NY67DsIwDEV~JfLAlL4oD7UDS8WABCyAWLqENKgRbRM5qQpC~DtuEYN9ratj-76hxwZyqL23eRmV0TAMoayF7lAJ6bXp5Es2yoXStGWk3K9a8XxqF0g0zrUCH4F~oQqa3vkyQhtYNFWyTtMVcLhDnixXHDRpHM85WBqSlIMk5dBRH00sTKUoBzFxnG5PMHpH0Y5e0d-0Qi~YYXrLiv9btr-czhOpJYGLJMyy6ZbwtHatlWocm7EzhXNs89MxkiVgVxECny8_]]"
    +"price": "41.99"
    +"oldPrice": "63.99"
    +"currencyCode": "EUR"
    +"description": "Neumático Crossmark de MaxxisDiseñado con el campeón del mundo Christoph Sauser, el CrossMark es la espectacular evolución del neumático de carreras de cross. La cresta central casi contínua vuela sobre suelos duros, pero tiene suficiente espacio para agarrarse a raíces y rocas húmedas. La cresta ligeramente elevada de los nudos laterales ofrece una precisión en el viraje nunca vista en un neumático tan rápido Especificaciones del neumático Crossmark de Maxxis:Tecnología LUST. Cresta central de rápido..."
    +"longDescription": "Neumático Crossmark de MaxxisDiseñado con el campeón del mundo Christoph Sauser, el CrossMark es la espectacular evolución del neumático de carreras de cross. La cresta central casi contínua vuela sobre suelos duros, pero tiene suficiente espacio para agarrarse a raíces y rocas húmedas. La cresta ligeramente elevada de los nudos laterales ofrece una precisión en el viraje nunca vista en un neumático tan rápido Especificaciones del neumático Crossmark de Maxxis:Tecnología LUST. Cresta central de rápido rodaje Nudos laterales elevados para un mayor viraje Tamaño: 26" x 2,1" TPI: 120 PSI máx.: 60 Durómetro: 70aCompre los Neumáticos Crossmark e Maxxis en Chain Reaction Cycles, la tienda de bicicletas online más grande del mundo."
    +"merchantCategoryPath": "Wheels & Tyres / Tyres"
    +"merchantMainCategory": "Wheels & Tyres"
    +"merchantSubCategory": "Tyres"
    +"smallImage": "http://media.chainreactioncycles.com/is/image/ChainReactionCycles/prod17336_Black_NE_01?$small$"
    +"mediumImage": "http://media.chainreactioncycles.com/is/image/ChainReactionCycles/prod17336_Black_NE_01?$detail$"
    +"largeImage": "http://media.chainreactioncycles.com/is/image/ChainReactionCycles/prod17336_Black_NE_01?$productfeedlarge$"
    +"lastModified": "2015-05-04T09:14:00"
    +"stockStatus": "1"
    +"savingsPercent": "34.00"
    +"savingsAbsolute": "22.00"
    +"program": "10453"
    +"deliveryTime": "4-7 working days"
    +"basePrice": "41.99"
    +"basePriceAmount": "1"
    +"basePriceText": "Per Product"
    +"shippingHandlingCosts": "5.99"
    +"shippingHandling": "Spain Standard Delivery"
    +"manufacturer": "Maxxis"
    +"color": "Black"
    +"size": "26""

     *
     * @param $productXml
     * @return Product
     */
    protected function createProduct($productXml)
    {
        $xmlRef = $this->slugify->slugify((string) $productXml->name);
        $product = $this->getProduct($xmlRef);

        $product->setUpc((string) $productXml->number);
//        $product->setIsbn((string) $productXml->productnumber);
//        $product->setEan((string) $productXml->ean);

        $product->setTitle((string) $productXml->name);
        $product->setDescription((string) $productXml->longDescription);
        $product->setSummary((string) $productXml->description);
        $product->setTags('');


        $product->setSaleprice((float) $productXml->price);
        $product->setOriginalprice((float) $productXml->oldPrice);
        $product->setUrl((string) $productXml->deepLink);


        $product->setImageSmall((string) $productXml->mediumImage);
        $product->setImageBig((string) $productXml->largeImage);

        $product->setBrand($this->getBrand((string) $productXml->manufacturer));


        $this->setCategory(
            $product,
            (string) $productXml->merchantCategoryPath,
            (string) $productXml->longDescription
        );

        return $this->persistProduct($product);
    }


}
