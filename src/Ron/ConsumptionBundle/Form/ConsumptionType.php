<?php

namespace Ron\ConsumptionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsumptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('energy')
            ->add('water')
            ->add('gas')
            ->add('createDate', 'date', array(
                    'widget' => 'single_text',
                    'format' => 'dd.MM.yyyy',
                    'attr' => array('type' => 'date')
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ron\ConsumptionBundle\Entity\Consumption'
        ));
    }

    public function getName()
    {
        return 'ron_consumptionbundle_consumptiontype';
    }
}
