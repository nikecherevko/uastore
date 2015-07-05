<?php

namespace Admin\CategoryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\CategoryBundle\Entity\Category;
use Admin\CategoryBundle\Form\CategoryType; 

/**
 * Category controller.
 *
 * @Route("/admin/category")
 */
class CategoryController extends Controller
{

    /**
     * Lists all Category entities.
     *
     * @Route("/", name="admin_category")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminCategoryBundle:Category')->findBy(
            ['parent_id' => 0],
            ['position' => 'ASC']
            );

        return ['entities' => $entities];
    }
    
    /**
     * Creates a new Category entity.
     *
     * @Route("/", name="admin_category_create")
     * @Method("POST")
     * @Template("AdminCategoryBundle:Category:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Category();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            
            if ($entity->getParentId() != 0) {
                
                $entityChild = $em->getRepository('AdminCategoryBundle:Category')->find($entity->getParentId());
                
                $entityChild->setCountchild($entityChild->getCountchild()+1);
            }
            
            $repository = $em->getRepository('AdminCategoryBundle:Category');
            
            
            $entities = $repository->findBy(
                    ['parent_id' => $entity->getParentId()],
                    ['position' => 'DESC'],
                    1);
            
             
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

            return $this->redirect($this->generateUrl('admin_category'));
        }
        
        return ['entity' => $entity,'form' => $form->createView()];
    }

    /**
     * Creates a form to create a Category entity.
     *
     * @param Category $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Category $entity, $id = 0)
    {

        $form = $this->createForm(new CategoryType(), $entity, [
            'action' => $this->generateUrl('admin_category_create'),
            'method' => 'POST',
        ]);
        
        $form->add('parent_id','hidden',['data' => $id]);

        return $form;
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Route("/{id}/new", name="admin_category_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id = 0)
    {
        
        $entity = new Category();
        $form   = $this->createCreateForm($entity, $id);

        return [
            'entity' => $entity,
            'id'     => $id,
            'form'   => $form->createView(),  
        ];
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{id}/edit", name="admin_category_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminCategoryBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
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
    * Creates a form to edit a Category entity.
    *
    * @param Category $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    
    private function createEditForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, [
            'action' => $this->generateUrl('admin_category_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        return $form;
    }
    
    /**
     * Edits an existing Category entity.
     *
     * @Route("/{id}", name="admin_category_update")
     * @Method("PUT")
     * @Template("AdminCategoryBundle:Category:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminCategoryBundle:Category')->find($id);

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
            
            return $this->redirect($this->generateUrl('admin_category_edit', ['id' => $id]));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }
 
    /**
     * Get Sub category entities.
     *
     * @Route("/getsubcategory/{id}", name="admin_category_getsubcategory")
     * @Template()
     */
    public function getSubCategoryAction()
    {
        $id = $this->getRequest()->get('id');        
        
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('AdminCategoryBundle:Category');

        $entities = $repository->findBy(
            ['parent_id' => $id],
            ['position' => 'ASC']);
        
        return ['entities' => $entities, 'id' => $id];
    }
    
    /**
     * Delete category entities.
     *
     * @Route("/delete/{id}", name="admin_category_delete")
     * @Template()
     */
    public function deleteAction()
    {
        
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminCategoryBundle:Category')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }
        
        if ($entity->getParentId() != 0) {
        
            $entityChild = $em->getRepository('AdminCategoryBundle:Category')->find($entity->getParentId());

            $entityChild->setCountchild($entityChild->getCountchild()-1);
        
        }
        
        $em->remove($entity);
        $em->flush();
        
    }
    
    /**
     * Get category for sort.
     *
     * @Route("/sort/{id}", name="admin_category_sort")
     * @Template()
     */
    public function sortAction()
    {
        $id = $this->getRequest()->get('id');        
        
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('AdminCategoryBundle:Category');
        
        $entities = $repository->findBy(
            ['parent_id' => $id],
            ['position' => 'ASC']);
        
        return ['entities' => $entities, 'id' => $id];
    }
    
    /**
     * Save sort category.
     *
     * @Route("/sortsave/", name="admin_category_sortsave")
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
            
            $product = $em->getRepository('AdminCategoryBundle:Category')->find(intval($recordIDValue));
            $product->setPosition($counter);
      
            $counter ++;
        }
        
        $em->flush();

        $repository = $em->getRepository('AdminCategoryBundle:Category');
        
        $entities = $repository->findBy(
            ['parent_id' => 0],
            ['position' => 'ASC']);
          
        $this->addFlash(
            'notice_success',
            'Данные успешно сохранены!'
        );
        
        return ['entities' => $entities];
    }
     
}
