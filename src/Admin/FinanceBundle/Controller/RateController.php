<?php

namespace Admin\FinanceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\FinanceBundle\Entity\Rate;
use Admin\FinanceBundle\Form\RateType;

/**
 * Rate controller.
 *
 * @Route("/admin/finance/rate")
 */
class RateController extends Controller
{

    /**
     * Lists all Rate entities.
     *
     * @Route("/", name="admin_finance_rate")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminFinanceBundle:Rate')->findAll();

        return ['entities' => $entities];
    }
    /**
     * Creates a new Rate entity.
     *
     * @Route("/", name="admin_finance_rate_create")
     * @Method("POST")
     * @Template("AdminFinanceBundle:Rate:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Rate();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash(
                'notice_success',
                'Валюта обмена успешно создана!'
            );

            return $this->redirect($this->generateUrl('admin_finance_rate'));
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Rate entity.
     *
     * @param Rate $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Rate $entity)
    {
        $form = $this->createForm(new RateType(), $entity, [
            'action' => $this->generateUrl('admin_finance_rate_create'),
            'method' => 'POST',
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new Rate entity.
     *
     * @Route("/new", name="admin_finance_rate_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Rate();
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Rate entity.
     *
     * @Route("/{id}/edit", name="admin_finance_rate_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:Rate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rate entity.');
        }

        $editForm = $this->createEditForm($entity);

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ];
    }

    /**
    * Creates a form to edit a Rate entity.
    *
    * @param Rate $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Rate $entity)
    {
        $form = $this->createForm(new RateType(), $entity, [
            'action' => $this->generateUrl('admin_finance_rate_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        return $form;
    }
    /**
     * Edits an existing Rate entity.
     *
     * @Route("/{id}", name="admin_finance_rate_update")
     * @Method("PUT")
     * @Template("AdminFinanceBundle:Rate:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:Rate')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rate entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->addFlash(
                'notice_success',
                'Данные успешно сохранены!'
            );

            return $this->redirect($this->generateUrl('admin_finance_rate_edit', ['id' => $id]));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ];
    }

     
    /**
     * Delete Rate entities.
     * 
     * @Route("/delete/{id}", name="admin_finance_rate_delete")
     * @Template()
     */
    public function deleteAction()
    {
        
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:Rate')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find rate entity.');
        }
        
        $em->remove($entity);
        $em->flush();
    }
    

    /**
     * Lists  Rate entities for main page.
     *
     * @Route("/ratemain/", name="admin_finance_ratemain")
     * @Template()
     */
    public function rateMainAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminFinanceBundle:Rate')->findAll();

        return ['entities' => $entities];
    }
}
