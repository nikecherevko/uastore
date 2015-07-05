<?php

namespace Admin\MenuBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MenuBundle\Entity\Category;
use Admin\MenuBundle\Form\CategoryType;

/**
 * Category controller.
 *
 * @Route("/admin/menu/category")
 */
class CategoryController extends Controller
{

    /**
     * Lists all Category entities.
     *
     * @Route("/", name="admin_menu_category")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryCategory = $em->getRepository('AdminMenuBundle:Category');

        $entities = $repositoryCategory->findBy(
            [],
            ['position' => 'ASC']);
        
        return ['entities' => $entities];
    }
    /**
     * Creates a new Category entity.
     *
     * @Route("/", name="admin_menu_category_create")
     * @Method("POST")
     * @Template("AdminMenuBundle:Category:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Category();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository('AdminMenuBundle:Category');
            $entities = $repository->findBy([], ['position' => 'DESC'],1);
            
            if ($entities) {
                $entity->setPosition($entities[0]->getPosition()+1);
            } else {
                $entity->setPosition(1);
            }
            
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash(
                'notice_success',
                'Категория успешно создана!'
            );
            return $this->redirect($this->generateUrl('admin_menu_category'));
        }
        return ['entity' => $entity,'form'   => $form->createView()];
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
            'action' => $this->generateUrl('admin_menu_category_create'),
            'method' => 'POST',
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Route("/new", name="admin_menu_category_new")
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
     * @Route("/{id}/edit", name="admin_menu_category_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMenuBundle:Category')->find($id);

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
        $form = $this->createForm(new CategoryType(), $entity, [
            'action' => $this->generateUrl('admin_menu_category_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        return $form;
    }
    /**
     * Edits an existing Category entity.
     *
     * @Route("/{id}", name="admin_menu_category_update")
     * @Method("PUT")
     * @Template("AdminMenuBundle:Category:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMenuBundle:Category')->find($id);

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
                        
            return $this->redirect($this->generateUrl('admin_menu_category_edit', ['id' => $id]));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }
    
    /**
     * Delete a Category menu entity.
     * 
     * @Route("/delete/{id}", name="admin_menu_category_del")
     * @Template()
     */
    public function deleteAction()
    {
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('AdminMenuBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $entityPage = $em->getRepository('AdminMenuBundle:Menu')->findBy(['category' => $id]);
        
        if ($entityPage) {
            throw $this->createNotFoundException('Category is not empty.');
        } else {
            $em->remove($entity);
            $em->flush();
        }
    }
    
    /**
     * Delete a Category sub menu entity.
     * 
     * @Route("/deletemenusub/{id}", name="admin_menu_categorysub_del")
     * @Template("AdminMenuBundle:Category:delete.html.twig")
     */
    public function deleteMenuSubAction()
    {
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('AdminMenuBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }
        
        $em->remove($entity);
        $em->flush();
        
    }
   
    /**
     * Get Category sub for Sort.
     * 
     * @Route("/sort/{id}", name="admin_menu_category_sort")
     * @Template()
     */
    public function sortAction()
    {
        $id = $this->getRequest()->get('id');        
        
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('AdminMenuBundle:Category');

        
        $entities = $repository->findBy([],
            ['position' => 'ASC']);
        
        return ['entities' => $entities, 'id' => $id];
    }
    
     /**
     * Get Menu sub for Sort.
     * 
     * @Route("/sortmenusub/{id}", name="admin_menu_categorysub_sort")
     * @Template()
     */
    public function sortMenuSubAction()
    {
        $id = $this->getRequest()->get('id');        
        
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('AdminMenuBundle:Menu');

        $entities = $repository->findBy(['category' => $id],
            ['position' => 'ASC']);
        
        return ['entities' => $entities, 'id' => $id];
    }
    
    /**
     * Sort Category Save.
     * 
     * @Route("/sortsave/", name="admin_menu_category_sortsave")
     * @Template()
     */
    public function sortSaveAction(Request $request)
    {
         
        $order = $request->request->get('order');
        $z = str_replace('ID[]=','',$order);
         
        $newOrder = explode("&",$z);
        $counter = 1;

        $em = $this->getDoctrine()->getManager();
        
        foreach ($newOrder as $recordIDValue) {
            
            $product = $em->getRepository('AdminMenuBundle:Category')->find(intval($recordIDValue));
            $product->setPosition($counter);
      
            $counter ++;
        }
        
        $em->flush();

        $repository = $em->getRepository('AdminMenuBundle:Category');
        
        $entities = $repository->findBy(
            [],
            ['position' => 'ASC']);
          
        $this->addFlash(
            'notice_success',
            'Данные успешно сохранены!'
        );
        
        return ['entities' => $entities];
    }
      
     /**
     * Sort Category Save.
     * 
     * @Route("/sortsavemenusub/", name="admin_menu_category_sortsavemenusub")
     * @Template("AdminMenuBundle:Category:sortSave.html.twig")
     */
    public function sortSaveMenuSubAction(Request $request)
    {
         
        $order = $request->request->get('order');
        $z = str_replace('ID[]=','',$order);
         
        $newOrder = explode("&",$z);
        $counter = 1;

        $em = $this->getDoctrine()->getManager();
        
        foreach ($newOrder as $recordIDValue) {
            
            $product = $em->getRepository('AdminMenuBundle:Menu')->find(intval($recordIDValue));
            $product->setPosition($counter);
      
            $counter ++;
        }
        
        $em->flush();

        $repositoryCategory = $em->getRepository('AdminMenuBundle:Category');
        
        $entities = $repositoryCategory->findBy(
            [],
            ['position' => 'ASC']);
          
        $this->addFlash(
            'notice_success',
            'Данные успешно сохранены!'
        );
        
        return ['entities' => $entities];
    }
    
    /**
     * Lists all Menu sub entities.
     * 
     * @Route("/getmenusub/{id}", name="admin_menu_category_getmenusub")
     * @Template()
     * 
     */
    public function getMenuSubAction()
    {
        $id = $this->getRequest()->get('id');        
        
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('AdminMenuBundle:Menu');

        $entities = $repository->findBy(
            ['category' => $id],
            ['position' => 'ASC']);
        
        return ['entities' => $entities, 'id' => $id];
    }
}