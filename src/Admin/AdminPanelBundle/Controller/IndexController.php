<?php

namespace Admin\AdminPanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Index controller.
 * 
 * @Route("/admin")
 */
class IndexController extends Controller
{

    /**
     * Main panel template.
     * @Route("/", name="homepage_admin")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        
        return[];
    }
    
    /**
     * Get user data.
     * @Template()
     */
    public function getUserDataAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
         
        $name = $user->getFirstname().' '.$user->getLastname();
        $photo = $user->getPhoto();
                
        return ['name' => $name, 'photo' => $photo];
    }
    
    
    /**
     * Get menu list.
     * 
     * @Template()
     */
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entitie = $em->getRepository('AdminMenuBundle:Category')->findBy([], ['position' => 'ASC']);
        
        foreach($entitie as $key => $val) {

            $entities[$val->getName()] = $em->getRepository('AdminMenuBundle:Menu')->findBy(['category' => $val->getId()], ['position' => 'ASC']);

            $route[$val->getName()] = $val->getRoute();

        }
        
        return ['entities' => $entities, 'route' => $route];
    }
     
}
