<?php

namespace Admin\NotebookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PropertiesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text', array('label' => 'Название'))
            ->add('file','file', ['label' => 'Логотип', 'required' => false,  'attr' => ["file_name" => "files[]"]])
            ->add('position','hidden')
            ->add('visible','hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\NotebookBundle\Entity\Properties'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_notebookbundle_properties';
    }
}
