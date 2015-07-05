<?php

namespace Admin\InfoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\InfoBundle\Entity\Category;
use Admin\InfoBundle\Form\CategoryType;

/**
 * Category controller.
 *
 * @Route("/admin/info/category")
 */
class CategoryController extends Controller
{

    /**
     * Lists all Category entities.
     *
     * @Route("/", name="admin_info_category")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminInfoBundle:Category')->findAll();

        return [
            'entities' => $entities,
        ];
    }
    /**
     * Creates a new Category entity.
     *
     * @Route("/", name="admin_info_category_create")
     * @Method("POST")
     * @Template("AdminInfoBundle:Category:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Category();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash(
                'notice_success',
                'Категория добавлена!'
            );


            return $this->redirect($this->generateUrl('admin_info_category', ['id' => $entity->getId()]));
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Category entity.
     *
     * @param Category $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, [
            'action' => $this->generateUrl('admin_info_category_create'),
            'method' => 'POST',
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Route("/new", name="admin_info_category_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Category();
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{id}/edit", name="admin_info_category_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminInfoBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $editForm = $this->createEditForm($entity);

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a Category entity.
    *
    * @param Category $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('admin_info_category_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ));

        return $form;
    }
    
    /**
     * Edits an existing Category entity.
     *
     * @Route("/{id}", name="admin_info_category_update")
     * @Method("PUT")
     * @Template("AdminInfoBundle:Category:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminInfoBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->addFlash(
                'notice_success',
                'Данные успешно сохранены!'
            );

            return $this->redirect($this->generateUrl('admin_info_category_edit', ['id' => $id]));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }
 
    /**
     * Delete a Category entity.
     *
     * @Route("/delete/{id}", name="admin_info_category_del")
     * @Template()
     */
    public function deleteAction()
    {
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('AdminInfoBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $entityPage = $em->getRepository('AdminInfoBundle:Page')->findBy(['category' => $id]);
        
        if ($entityPage) {
            throw $this->createNotFoundException('Category is not empty.');
        } else {
            $em->remove($entity);
            $em->flush();
        }
        
    }
    
}
