<?php
namespace AppBundle\Service;
use Symfony\Component\Intl\Intl;

class CountryNamer
{   
    /**
     * Return the complete name of a country 
     *
     */
    public function getCountryName($country)
    {
        return  Intl::getRegionBundle()->getCountryName($country);
    }
}