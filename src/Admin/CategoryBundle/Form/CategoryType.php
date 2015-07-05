<?php

namespace Admin\CategoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent_id','hidden')
            ->add('title','text', array('label' => 'Название', 'attr' => array('placeholder' => 'Название категории')))
            ->add('file','file', array('label' => 'Логотип', 'required' => false,  'attr' => array("file_name" => "files[]")))
            ->add('position','hidden')
            ->add('count','hidden')
            ->add('countchild','hidden')
            ->add('visible','checkbox', array('label' => 'Видимость', 'required' => false, 'value' => 0))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\CategoryBundle\Entity\Category'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_categorybundle_category';
    }
}
