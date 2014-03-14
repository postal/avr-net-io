<?php
namespace Ron\RaspberryPiBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $meAboutnu = $factory->createItem('root');

        $menu->addChild('Startseite', array('route' => 'avr_net_io_homepage'));
        $menu->addChild('Schalter', array('route' => 'rpi_input'));

        return $menu;
    }
}