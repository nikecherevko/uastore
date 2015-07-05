<?php

namespace Admin\FinanceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',['label' => 'Название'])
            ->add('description','textarea',['label' => 'Подсказка'])    
            ->add('rate','money',['label' => 'Курс', 'attr' => ['style' => 'width: 5em']])
            ->add('active','checkbox', ['label' => 'Видимость', 'required' => false, 'value' => 0])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\FinanceBundle\Entity\Payment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_financebundle_payment';
    }
}
