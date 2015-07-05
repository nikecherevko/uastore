<?php

namespace Admin\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\CommonBundle\Entity\Color;
use Admin\CommonBundle\Form\ColorType;

/**
 * Color controller.
 *
 * @Route("/admin/color")
 */
class ColorController extends Controller
{

    /**
     * Lists all Color entities.
     *
     * @Route("/", name="admin_color")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminCommonBundle:Color')->findAll();

        return [
            'entities' => $entities,
        ];
    }
    /**
     * Creates a new Color entity.
     *
     * @Route("/", name="admin_color_create")
     * @Method("POST")
     * @Template("AdminCommonBundle:Color:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Color();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash(
                'notice_success',
                'Цвет успешно создан!'
            );

            return $this->redirect($this->generateUrl('admin_color', ['id' => $entity->getId()]));
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Color entity.
     *
     * @param Color $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Color $entity)
    {
        $form = $this->createForm(new ColorType(), $entity, [
            'action' => $this->generateUrl('admin_color_create'),
            'method' => 'POST',
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new Color entity.
     *
     * @Route("/new", name="admin_color_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Color();
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Color entity.
     *
     * @Route("/{id}/edit", name="admin_color_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminCommonBundle:Color')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Color entity.');
        }

        $editForm = $this->createEditForm($entity);

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a Color entity.
    *
    * @param Color $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Color $entity)
    {
        $form = $this->createForm(new ColorType(), $entity, [
            'action' => $this->generateUrl('admin_color_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ]);

        return $form;
    }
    /**
     * Edits an existing Color entity.
     *
     * @Route("/{id}", name="admin_color_update")
     * @Method("PUT")
     * @Template("AdminCommonBundle:Color:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminCommonBundle:Color')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Color entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->addFlash(
                'notice_success',
                'Данные успешно сохранены!'
            );

            return $this->redirect($this->generateUrl('admin_color_edit', ['id' => $id]));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }
    
       
      
    /**
     * Delete Color entities.
     * 
     * @Route("/delete/{id}", name="admin_color_delete")
     * @Template()
     */
    public function deleteAction()
    {
        
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminCommonBundle:Color')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Color entity.');
        }
        
        $em->remove($entity);
        $em->flush();
    }
  
}
