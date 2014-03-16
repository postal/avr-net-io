<?php
namespace Ron\RaspberryPiBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Statistik', array('route' => 'consumption'));
        $menu['Statistik']->addChild('Übersicht', array('route' => 'consumption'));
        $menu['Statistik']->addChild('Neu', array('route' => 'consumption_new'));
        $menu->addChild('Bewegung', array('route' => 'ron_raspberry_pi_pirs'));
        $menu['Bewegung']->addChild(
            'Letzte Stunde',
            array(
                'route' => 'ron_raspberry_pi_pir',
                'routeParameters' => array('period' => 'hour')
            )
        );
        $menu['Bewegung']->addChild(
            'Heute',
            array(
                'route' => 'ron_raspberry_pi_pir',
                'routeParameters' => array('period' => 'day')
            )
        );
        $menu['Bewegung']->addChild(
            'Letzte Woche',
            array(
                'route' => 'ron_raspberry_pi_pir',
                'routeParameters' => array('period' => 'week')
            )
        );
        $menu['Bewegung']->addChild(
            'Letzer Monat',
            array(
                'route' => 'ron_raspberry_pi_pir',
                'routeParameters' => array('period' => 'month')
            )
        );
        $menu['Bewegung']->addChild(
            'Dieses Jahr',
            array(
                'route' => 'ron_raspberry_pi_pir',
                'routeParameters' => array('period' => 'year')
            )
        );
        $menu->addChild('Temperatur', array('route' => 'avr_output_temp'));
        $menu['Temperatur']->addChild(
            'Letzte Stunde',
            array(
                'route' => 'avr_output_temp_period',
                'routeParameters' => array('period' => 'hour')
            )
        );
        $menu['Temperatur']->addChild(
            'Heute',
            array(
                'route' => 'avr_output_temp_period',
                'routeParameters' => array('period' => 'day')
            )
        );
        $menu['Temperatur']->addChild(
            'Letzte Woche',
            array(
                'route' => 'avr_output_temp_period',
                'routeParameters' => array('period' => 'week')
            )
        );
        $menu['Temperatur']->addChild(
            'Letzer Monat',
            array(
                'route' => 'avr_output_temp_period',
                'routeParameters' => array('period' => 'month')
            )
        );
        $menu['Temperatur']->addChild(
            'Dieses Jahr',
            array(
                'route' => 'avr_output_temp_period',
                'routeParameters' => array('period' => 'year')
            )
        );
        $menu->addChild('Schalter', array('route' => 'ron_raspberry_pi_switch'));


        return $menu;
    }

    public function menuRight(FactoryInterface $factory, array $options)
    {
        $menuRight = $factory->createItem('root');
        $menuRight->addChild('Logout', array('route' => '_logout'));

        return $menuRight;
    }
}