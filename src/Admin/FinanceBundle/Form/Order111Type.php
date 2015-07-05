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
            ->add('category','category_selector', ['data' => $options['category_id'], 'data_class' => null])  
            ->add('notebook','notebook_selector', ['data' => $options['notebook_id'], 'data_class' => null])  
            ->add('user','user_selector')    
            ->add('payment', null, ['label' => 'Способ оплаты'])
            ->add('price','hidden', ['data' => $options['price']])
            ->add('customer','text', ['label' => 'Фамилия и Имя'])
            ->add('address','text', ['label' => 'Адрес доставки'])
            ->add('telephone', 'text',['label' => 'Мобильный телефон'])
            ->add('state','hidden', ['data' => 1])
            ->add('count','hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\FinanceBundle\Entity\Order',
            'category_id' => null,
            'notebook_id' => null,
            'price' => null
        ))
        ->setRequired(array('em'))
        ->setAllowedTypes('em', 'Doctrine\Common\Persistence\ObjectManager')    
        ;
    }
    
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_financebundle_order';
    }
}
