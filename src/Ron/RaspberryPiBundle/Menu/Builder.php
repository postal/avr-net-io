<?php
namespace Ron\RaspberryPiBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class Builder
 * @package Ron\RaspberryPiBundle\Menu
 */
class Builder extends ContainerAware
{
    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
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

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function menuRight(FactoryInterface $factory, array $options)
    {
        $menuRight = $factory->createItem('root');
        $menuRight->addChild('Logout', array('route' => '_logout'));

        return $menuRight;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function menuTemperatureFooter(FactoryInterface $factory, array $options)
    {
        $menuTemperatureFooter = $factory->createItem('root');
        $menuTemperatureFooter->addChild(
            'Letzte Stunde',
            array(
                'route' => 'avr_output_temp_period',
                'routeParameters' => array('period' => 'hour')
            )
        );
        $menuTemperatureFooter->addChild(
            'Heute',
            array(
                'route' => 'avr_output_temp_period',
                'routeParameters' => array('period' => 'day')
            )
        );
        $menuTemperatureFooter->addChild(
            'Letzte Woche',
            array(
                'route' => 'avr_output_temp_period',
                'routeParameters' => array('period' => 'week')
            )
        );
        $menuTemperatureFooter->addChild(
            'Letzer Monat',
            array(
                'route' => 'avr_output_temp_period',
                'routeParameters' => array('period' => 'month')
            )
        );
        $menuTemperatureFooter->addChild(
            'Dieses Jahr',
            array(
                'route' => 'avr_output_temp_period',
                'routeParameters' => array('period' => 'year')
            )
        );

        return $menuTemperatureFooter;
    }

    public function menuPirFooter(FactoryInterface $factory, array $options)
    {
        $menuPirFooter = $factory->createItem('root');
        $menuPirFooter->addChild(
            'Letzte Stunde',
            array(
                'route' => 'ron_raspberry_pi_pir',
                'routeParameters' => array('period' => 'hour')
            )
        );
        $menuPirFooter->addChild(
            'Heute',
            array(
                'route' => 'ron_raspberry_pi_pir',
                'routeParameters' => array('period' => 'day')
            )
        );
        $menuPirFooter->addChild(
            'Letzte Woche',
            array(
                'route' => 'ron_raspberry_pi_pir',
                'routeParameters' => array('period' => 'week')
            )
        );
        $menuPirFooter->addChild(
            'Letzer Monat',
            array(
                'route' => 'ron_raspberry_pi_pir',
                'routeParameters' => array('period' => 'month')
            )
        );
        $menuPirFooter->addChild(
            'Dieses Jahr',
            array(
                'route' => 'ron_raspberry_pi_pir',
                'routeParameters' => array('period' => 'year')
            )
        );

        return $menuPirFooter;
    }
}