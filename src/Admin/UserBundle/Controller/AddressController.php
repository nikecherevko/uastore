<?php

namespace Admin\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\UserBundle\Entity\Address;
use Admin\UserBundle\Form\AddressType;

/**
 * Address controller.
 *
 * @Route("/admin/user/address")
 */
class AddressController extends Controller
{

    /**
     * Lists all Address entities.
     *
     * @Route("/", name="admin_user_address")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminUserBundle:Address')->findAll();

        return ['entities' => $entities];
    }
    /**
     * Creates a new Address entity.
     *
     * @Route("/", name="admin_user_address_create")
     * @Method("POST")
     * @Template("AdminUserBundle:Address:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Address();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_address_show', ['id' => $entity->getId()]));
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Address entity.
     *
     * @param Address $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Address $entity)
    {
        $form = $this->createForm(new AddressType(), $entity, [
            'action' => $this->generateUrl('admin_user_address_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Displays a form to create a new Address entity.
     *
     * @Route("/new", name="admin_user_address_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Address();
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Address entity.
     *
     * @Route("/{id}", name="admin_user_address_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUserBundle:Address')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Address entity.');
        }

        return ['entity' => $entity];
    }

    /**
     * Displays a form to edit an existing Address entity.
     *
     * @Route("/{id}/edit", name="admin_user_address_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUserBundle:Address')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Address entity.');
        }

        $editForm = $this->createEditForm($entity);

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a Address entity.
    *
    * @param Address $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Address $entity)
    {
        $form = $this->createForm(new AddressType(), $entity, [
            'action' => $this->generateUrl('admin_user_address_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        return $form;
    }
    /**
     * Edits an existing Address entity.
     *
     * @Route("/{id}", name="admin_user_address_update")
     * @Method("PUT")
     * @Template("AdminUserBundle:Address:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminUserBundle:Address')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Address entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_address_edit', ['id' => $id]));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }
   
 }
