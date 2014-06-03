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

class SwitchesType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            $builder
                ->add(
                    'switches',
                    'collection',
                    array(
                        'type' => new SwitchType(),
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