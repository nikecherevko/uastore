<?php

namespace Admin\ContactBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\ContactBundle\Entity\Telephone;
use Admin\ContactBundle\Form\TelephoneType;

/**
 * Telephone controller.
 *
 * @Route("/admin/contact/telephone")
 */
class TelephoneController extends Controller
{

    /**
     * Lists all Telephone entities.
     *
     * @Route("/", name="admin_contact_telephone")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminContactBundle:Telephone')->findAll();

        return ['entities' => $entities];
    }
    /**
     * Creates a new Telephone entity.
     *
     * @Route("/", name="admin_contact_telephone_create")
     * @Method("POST")
     * @Template("AdminContactBundle:Telephone:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Telephone();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

                        
            $this->addFlash(
                'notice_success',
                'Телефон добавлен!'
            );
            
            return $this->redirect($this->generateUrl('admin_contact_telephone', ['id' => $entity->getId()]));
        }

        return ['entity' => $entity,'form'   => $form->createView()];
    }

    /**
     * Creates a form to create a Telephone entity.
     *
     * @param Telephone $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Telephone $entity)
    {
        $form = $this->createForm(new TelephoneType(), $entity, [
            'action' => $this->generateUrl('admin_contact_telephone_create'),
            'method' => 'POST',
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new Telephone entity.
     *
     * @Route("/new", name="admin_contact_telephone_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Telephone();
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Telephone entity.
     *
     * @Route("/{id}/edit", name="admin_contact_telephone_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminContactBundle:Telephone')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Telephone entity.');
        }

        $editForm = $this->createEditForm($entity);
        $imagepath = $entity->getImage();
        
        return [
            'entity'      => $entity,
            'imagepath'   => $imagepath,
            'edit_form'   => $editForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a Telephone entity.
    *
    * @param Telephone $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Telephone $entity)
    {
        $form = $this->createForm(new TelephoneType(), $entity, array(
            'action' => $this->generateUrl('admin_contact_telephone_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Telephone entity.
     *
     * @Route("/{id}", name="admin_contact_telephone_update")
     * @Method("PUT")
     * @Template("AdminContactBundle:Telephone:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminContactBundle:Telephone')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Telephone entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->addFlash(
                'notice_success',
                'Данные обновлены!'
            );
                    
            return $this->redirect($this->generateUrl('admin_contact_telephone_edit', ['id' => $id]));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }
    
    /**
     * Delete Telephone entities.
     *
     * @Route("/delete/{id}", name="admin_contact_telephone_delete")
     * @Template()
     */
    public function deleteAction()
    {
        
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminContactBundle:Telephone')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Telephone entity.');
        }
        
        $em->remove($entity);
        $em->flush();
        
    }
    
}
