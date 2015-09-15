<?php
namespace AppBundle\Component\Feed\Importer\Adapt;


class BrandAdapt
{
    protected static $brandNamesOriginal = array(
        'Rudy project'      => 'Rudy-project',
        'Adidas-eyewear'    => 'Adidas',
        'X bionic'          => 'X-bionic',
        'X socks'           => 'X-socks',
        'Swiss stop'        => 'Swissstop',
        'Sixsixone 661'     => 'Sixsixone',
        'Selle-royal'       => 'Selle royal',
        'Selle-italia'      => 'Selle italia',
        'Sci-con'           => 'Scicon',
        'Santini'           => 'Santini sms',
    );

    public static function getOriginalName($name)
    {
        $name = ucfirst(strtolower($name));

        if (array_key_exists($name, self::$brandNamesOriginal)) {
            $name = self::$brandNamesOriginal[$name];
        }

        return $name;
    }
}
