<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Admin\CategoryBundle\AdminCategoryBundle(),
            new Frontend\StoreBundle\FrontendStoreBundle(),
            new Admin\UserBundle\AdminUserBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Admin\AdminPanelBundle\AdminAdminPanelBundle(),
            new Admin\InfoBundle\AdminInfoBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new FM\ElfinderBundle\FMElfinderBundle(),
            new Admin\MenuBundle\AdminMenuBundle(),
            new Admin\FinanceBundle\AdminFinanceBundle(),
            new Admin\ContactBundle\AdminContactBundle(),
            new Admin\CommonBundle\AdminCommonBundle(),
            new Admin\NotebookBundle\AdminNotebookBundle(),
            new Avalanche\Bundle\ImagineBundle\AvalancheImagineBundle(),
            new Frontend\PanelBundle\FrontendPanelBundle(),
            new Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
    
    public function init()
    {
        date_default_timezone_set( 'Europe/Kiev' );
        parent::init();
    }
}
