<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ron
 * Date: 23.05.13
 * Time: 08:36
 * To change this template use File | Settings | File Templates.
 */

namespace Ron\ConsumptionBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsumptionType extends AbstractTypepe
{

    /**
     * @param FormBuilderInterfacerface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterfacerface $builder, array $options)
    {
        $builder
            ->add('value')
            ->add('createdAt');
    }

    /**
     * @param OptionsResolverInterfaceterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterfaceterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ron\ConsumptionBundle\Entity\Energy'
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