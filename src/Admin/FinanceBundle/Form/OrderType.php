<?php

namespace Admin\FinanceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      
        $builder
            ->add('user','user_selector')    
            ->add('payment', null, ['label' => 'Способ оплаты'])
            ->add('price','text', ['label' => 'Общая сумма'])
            ->add('customer','text', ['label' => 'Фамилия и Имя'])
            ->add('address','text', ['label' => 'Адрес доставки'])
            ->add('telephone', 'text',['label' => 'Мобильный телефон'])
            ->add('state','choice', [
                'choices' => [
                                '0' => 'не подтвержден',
                                '1' => 'подтвержден',
                                '2' => 'отменён',
                                '3' => 'доставлено'
                            ]
                ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\FinanceBundle\Entity\Order'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_financebundle_order';
    }
}
