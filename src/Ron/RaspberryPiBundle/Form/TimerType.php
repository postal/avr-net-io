<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 14.03.14
 * Time: 21:04
 */

namespace Ron\RaspberryPiBundle\Form;


use Ron\RaspberryPiBundle\SwitchEntity;
use Ron\RaspberryPiBundle\TimerEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TimerType extends AbstractType
{


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /**
                 * @var $timer TimerEntity
                 */

                $timer = $event->getData();
                $form = $event->getForm();
                foreach ($timer->getTime() as $key => $time) {
                    $form->add('submitTimer'.$key, 'submit', array('label' => $time . 's'));
                }
            }
        );

    }


    /**
     * Returns the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return "raspi_timer";
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(
            array(
                'data_class' => 'Ron\RaspberryPiBundle\TimerEntity',
            )
        );

    }


}