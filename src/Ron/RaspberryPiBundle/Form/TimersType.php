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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TimersType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add(
                'timers',
                'collection',
                array(
                    'type' => new TimerType(),
                    'required' => false,
                )
            );


    }


    /**
     * Returns the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return "raspi_switches";
    }


} 