<?php

namespace Admin\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TelephoneType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('telephone','text', ['label' => 'Номер телефона'])
            ->add('file','file', ['label' => 'Логотип', 'required' => false,  'attr' => ["file_name" => "files[]"]])
            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\ContactBundle\Entity\Telephone'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_contactbundle_telephone';
    }
}
