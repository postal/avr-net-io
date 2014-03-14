<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 14.03.14
 * Time: 21:04
 */

namespace Ron\RaspberryPiBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SwitchType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('switch1', 'checkbox',
                array(
                    'label' => '-----',
                    'required' => false)
            )
            ->add('switch2', 'checkbox',
                array(
                    'label' => 'Wohnzimmerlampe',
                    'required' => false)
            )
            ->add('switch3', 'checkbox',
                array(
                    'label' => 'Flurlicht',
                    'required' => false)
            )
            ->add('switch4', 'checkbox',
                array(
                    'label' => '----',
                    'required' => false)
            )
            ->add('submit', 'submit');
    }


    /**
     * Returns the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return "raspi_switch";
    }


} 