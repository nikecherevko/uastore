<?php

namespace Admin\CommonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ColorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameru','text',['label' => 'Название (рус.)'])
            ->add('nameeng','text',['label' => 'Название (анг.)'])
            ->add('hex','text',['label' => 'HEX цвета', 'attr' => ['class' => 'color']])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\CommonBundle\Entity\Color'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_commonbundle_color';
    }
}
