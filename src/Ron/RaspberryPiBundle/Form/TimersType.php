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

class TimersType extends AbstractType
{
    protected $timerConfigs;

    /**
     * @param $timerConfigs
     */
    public function __construct($timerConfigs)
    {
        $this->setTimerConfigs($timerConfigs);
    }

    /**
     * @param mixed $timerConfigs
     */
    public function setTimerConfigs($timerConfigs)
    {
        $this->timerConfigs = $timerConfigs;
    }

    /**
     * @return mixed
     */
    public function getTimerConfigs()
    {
        return $this->timerConfigs;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add(
                'timers',
                'collection',
                array(
                    'type' => new TimerType($this->getTimerConfigs()),
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