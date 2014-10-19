<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 14.03.14
 * Time: 21:04
 */

namespace Ron\RaspberryPiBundle\Form;


use Ron\RaspberryPiBundle\SwitchEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TimerType extends AbstractType
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->setConfig($config);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->getTimes() as $key => $time) {
            $builder->add('submitTimer' . $key, 'submit', array('label' => $time . 's'));
        }

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
                'data_class' => 'Ron\RaspberryPiBundle\SwitchEntity',
            )
        );

    }


    /**
     * @return array
     */
    protected function getTimes()
    {
        $config = $this->getConfig();
        $times = $config['times'];

        return (array)$times;
    }
}