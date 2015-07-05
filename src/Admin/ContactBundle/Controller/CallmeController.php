<?php

namespace Admin\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Callme controller.
 *
 * @Route("/admin/contact/callme")
 */
class CallmeController extends Controller
{

    /**
     * Lists all Callme entities is active.
     *
     * @Route("/", name="admin_contact_callme")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminContactBundle:Callme')->findBy(['active' => 1]);
        
        return ['entities' => $entities];
    }
    
    /**
     * Processed Callme entities.
     * @Route("/process/{id}", name="admin_contact_process")
     * @Template()
     */
    public function processAction()
    {
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('AdminContactBundle:Callme')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find callme entity.');
        }
        
        $entity->setActive(0);
        $em->flush();
        
        return[];
    }
    
    /**
     * Lists all Callme entities.
     *
     * @Route("/getall/", name="admin_contact_callme_getall")
     * @Method("GET")
     * @Template()
     */
    public function getAllAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminContactBundle:Callme')->findBy(
            [],
            ['created' => 'DESC']);
        
        return ['entities' => $entities];
    }
    
    /**
     * Get list all Callme entities active and processed.
     *
     * @Route("/process/{id}", name="admin_contact_callme_process")
     * @Template()
     */
    public function getProcessAction()
    {
        $em = $this->getDoctrine()->getManager();

        $repo= $em->getRepository('AdminContactBundle:Callme');
        
        $qb = $repo->createQueryBuilder('a');
        $qb->select('COUNT(a)');
        $qb->where('a.active = 1');

        $countActive = $qb->getQuery()->getSingleScalarResult();
        
        $qb->select('COUNT(a)');
        $qb->where('a.active = 0');

        $countNoactive = $qb->getQuery()->getSingleScalarResult();
        
        return ['active' => $countActive, 'noactive' => $countNoactive];
        
    }
  
}
