<?php

namespace Admin\NotebookBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class NotebookType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('manufacturer','text', array('label' => 'Производитель'))
            ->add('image','hidden')
            ->add('model','text', array('label' => 'Модель'))
            ->add('modification','text', array('label' => 'Модификация', 'required' => false))
            ->add('price','text', array('label' => 'Цена'))
            ->add('color','text', array('label' => 'Цвет'))
            ->add('code','text', array('label' => 'Код'))
            ->add('cpu_series','text', array('label' => 'Серия процессора'))
            ->add('cpu_core','text', array('label' => 'Количесвто ядер процессора'))
            ->add('cpu_frequency','text', array('label' => 'Частота процессора'))
            ->add('display','text', array('label' => 'Экран'))
            ->add('display_resolution','text', array('label' => 'Разрешение экрана'))
            ->add('display_cover','text', array('label' => 'Покрытие экрана'))
            ->add('videoadapter','text', array('label' => 'Видеокарта'))
            ->add('videoadapter_type','text', array('label' => 'Тип видеокарты'))
            ->add('videoadapter_manufacturer','text', array('label' => 'Производитель видеокарты'))
            ->add('ram_size','text', array('label' => 'Объем оперативной памяти'))
            ->add('ram_type','text', array('label' => 'Тип оперативной памяти'))
            ->add('hdd_type','text', array('label' => 'Тип наколпителя'))
            ->add('hdd_size','text', array('label' => 'Объем наколпителя'))
            ->add('optical_drive','text', array('label' => 'Оптический привод'))
            ->add('volume','text', array('label' => 'Звуковая система', 'required' => false))
            ->add('webcam','text', array('label' => 'Веб камера'))
            ->add('lan','text', array('label' => 'Сетевой адаптер', 'required' => false))
            ->add('wi_fi','text', array('label' => 'Wi-Fi', 'required' => false))
            ->add('bluetooth','text', array('label' => 'Bluetooth', 'required' => false))
            ->add('usb_two','text', array('label' => 'USB 2.0', 'required' => false))
            ->add('usb_three','text', array('label' => 'USB 3.0', 'required' => false))
            ->add('hdmi','text',array('label' => 'HDMI', 'required' => false))
            ->add('batteries','text', array('label' => 'Аккумулятор'))
            ->add('batteries_size','text', array('label' => 'Емкость аккумулятора', 'required' => false))
            ->add('os','text', array('label' => 'Операционная система'))
            ->add('size','text', array('label' => 'Габариты', 'required' => false))
            ->add('weight','text', array('label' => 'Вес'))
            ->add('packaging','textarea', array('label' => 'Комплектация', 'required' => false, 'attr' => ['class' => 'textareanote']))
            ->add('additionally','textarea', array('label' => 'Дополнительно', 'required' => false , 'attr' => ['class' => 'textareanote']))
            ->add('coloreng','hidden')
            ->add('rate','hidden')
            ->add('view','hidden')
            ->add('comment_count','hidden')
            ->add('rate_count','hidden')
            ->add('pay_count','hidden')
            ->add('notebook_count','text', array('label' => 'Количество'))
            ->add('category','category_selector', ['data' => $options['category_id'], 'data_class' => null]) 
            ->add('description', 'ckeditor', array(
                'label' => false,
                'required' => false,
                'config' => array(
                'filebrowserBrowseRoute' => 'elfinder',
                'filebrowserBrowseRouteParameters' => array('instance' => 'default')
                )
                )
                )     
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\NotebookBundle\Entity\Notebook',
            'category_id' => null,
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
        return 'admin_notebookbundle_notebook';
    }
}
