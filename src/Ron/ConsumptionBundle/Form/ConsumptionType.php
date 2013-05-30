<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ron
 * Date: 23.05.13
 * Time: 08:36
 * To change this template use File | Settings | File Templates.
 */

namespace Ron\ConsumptionBundle\Form;


use Ron\ConsumptionBundle\Entity\Energy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsumptionType extends AbstractType
{

    /**
     * @param FormBuilderInterfacerface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gas', 'text') #new GasType())
            ->add('energy', 'text') #new EnergyType())
            ->add('water', 'text')#new WaterType())
            ;
    }

    /**
     * @param OptionsResolverInterfaceterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ron\ConsumptionBundle\Entity\Consumption'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ron_consumptionbundle_consumptiontype';
    }
}