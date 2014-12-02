<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 14.03.14
 * Time: 21:04
 */

namespace Ron\RaspberryPiBundle\Form;


use Ron\RaspberryPiBundle\TimerEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TimerType extends AbstractType
{
    /**
     * @var array
     */
    protected $timeUnitShortcut = array(
        'minutes' => 'min',
        'hours' => 'h',
        'days' => 'd',
        'weeks' => 'W',
    );

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
                foreach ($timer->getTimes() as $key => $time) {
                    $label = $time . $this->getShortcutByTimeUnitName($timer->getTimeUnit());
                    $form->add('submitTimer' . $key, 'submit', array('label' => $label));
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

    /**
     * @param $name
     * @return string
     */
    protected function getShortcutByTimeUnitName($name)
    {
        if (in_array($name, array_keys($this->timeUnitShortcut))) {
            return $this->timeUnitShortcut[$name];
        }

        return $name;
    }
}