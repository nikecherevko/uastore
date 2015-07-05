<?php

namespace Admin\NotebookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\NotebookBundle\Entity\Properties;
use Admin\NotebookBundle\Form\PropertiesType;

/**
 * Properties controller.
 *
 * @Route("/admin/notebook/properties")
 */
class PropertiesController extends Controller
{

    /**
     * Lists all Properties entities.
     *
     * @Route("/", name="admin_notebook_properties")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminNotebookBundle:Properties')->findBy(
            [],
            ['position' => 'ASC']
        );
       
        return [
            'entities' => $entities
        ];
    }
    /**
     * Creates a new Properties entity.
     *
     * @Route("/", name="admin_notebook_properties_create")
     * @Method("POST")
     * @Template("AdminNotebookBundle:Properties:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Properties();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            
            $repository = $em->getRepository('AdminNotebookBundle:Properties');
            
            $entities = $repository->findBy(
                    [],
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
                'Категория добавлена!'
            );
            
            return $this->redirect($this->generateUrl('admin_notebook_properties_new'));
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView()
        ];
    }

    /**
     * Creates a form to create a Properties entity.
     *
     * @param Properties $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Properties $entity)
    {
        $form = $this->createForm(new PropertiesType(), $entity, [
            'action' => $this->generateUrl('admin_notebook_properties_create'),
            'method' => 'POST',
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new Properties entity.
     *
     * @Route("/new", name="admin_notebook_properties_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Properties();
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Properties entity.
     *
     * @Route("/{id}/edit", name="admin_notebook_properties_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Properties')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Properties entity.');
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
    * Creates a form to edit a Properties entity.
    *
    * @param Properties $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Properties $entity)
    {
        $form = $this->createForm(new PropertiesType(), $entity, [
            'action' => $this->generateUrl('admin_notebook_properties_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        return $form;
    }
    /**
     * Edits an existing Properties entity.
     *
     * @Route("/{id}", name="admin_notebook_properties_update")
     * @Method("PUT")
     * @Template("AdminNotebookBundle:Properties:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Properties')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Properties entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->addFlash(
                'notice_success',
                'Данные успешно сохранены!'
            );
            
            return $this->redirect($this->generateUrl('admin_notebook_properties_edit', ['id' => $id]));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
   
    /**
     * Delete Properties entities.
     * 
     * @Route("/delete/{id}", name="admin_notebook_properties_delete")
     * @Template()
     */
    public function deleteAction()
    {
        
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Properties')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Properties entity.');
        }
        
        $em->remove($entity);
        $em->flush();
    }
    
    /**
     * Delete Value entities.
     * 
     * @Route("/deletevalue/{id}", name="admin_notebook_properties_deletevalue")
     * @Template("AdminNotebookBundle:Properties:delete.html.twig")
     */
    public function deleteValueAction()
    {
        
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Value')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Value entity.');
        }
        
        $em->remove($entity);
        $em->flush();
        
        return $this->render('AdminNotebookBundle:Properties:delete.html.twig');
        
    }
    /**
     * Get Properties for sort.
     * 
     * @Route("/sort/{id}", name="admin_notebook_properties_sort")
     * @Template()
     */
    public function sortAction()
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('AdminNotebookBundle:Properties');
        
        $entities = $repository->findBy(
            [],
            ['position' => 'ASC']);
        
        return ['entities' => $entities];
    }
    
        
     /**
      * Get Vslue for Sort.
      * 
      * @Route("/sortvalue/{id}", name="admin_notebook_properties_sortvalue")
      * @Template()
      */
    public function sortValueAction()
    {
        $id = $this->getRequest()->get('id');        
        
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('AdminNotebookBundle:Value');

        $entities = $repository->findBy(
            ['property' => $id],
            ['position' => 'ASC']);
        
        return ['entities' => $entities, 'id' => $id];
    }
    
    /**
     * Sort Properties Save.
     * 
     * @Route("/sortsave/", name="admin_notebook_properties_sortsave")
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
            
            $product = $em->getRepository('AdminNotebookBundle:Properties')->find(intval($recordIDValue));
            $product->setPosition($counter);
      
            $counter ++;
        }
        
        $em->flush();

        $repository = $em->getRepository('AdminNotebookBundle:Properties');

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
     * Sort Properties Save.
     * 
     * @Route("/sortvaluesave/", name="admin_notebook_properties_sortvaluesave")
     * @Template("AdminNotebookBundle:Properties:sortSave.html.twig")
     */
    public function sortValueSaveAction(Request $request)
    {
         
        $order = $request->request->get('order');
        $z = str_replace('ID[]=','',$order);
         
        $newOrder = explode("&",$z);
        $counter = 1;

        $em = $this->getDoctrine()->getManager();
        
        foreach ($newOrder as $recordIDValue) {
            
            $product = $em->getRepository('AdminNotebookBundle:Value')->find(intval($recordIDValue));
            $product->setPosition($counter);
      
            $counter ++;
        }
        
        $em->flush();

        $repository = $em->getRepository('AdminNotebookBundle:Properties');
        
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
     * Lists all Filter sub entities.
     * 
     * @Route("/getvalue/{id}", name="admin_notebook_properties_getvalue")
     * @Template()
     */
    public function getValueAction()
    {
        $id = $this->getRequest()->get('id');        
        
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('AdminNotebookBundle:Value');

        $entities = $repository->findBy(
            ['property' => $id],
            ['position' => 'ASC']);
        
        return ['entities' => $entities, 'id' => $id];
    }
      
}