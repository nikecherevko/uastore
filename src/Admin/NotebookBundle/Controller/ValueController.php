<?php

namespace Admin\NotebookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\NotebookBundle\Entity\Value;
use Admin\NotebookBundle\Form\ValueType;

/**
 * Value controller.
 *
 * @Route("/admin/notebook/value")
 */
class ValueController extends Controller
{

    /**
     * Lists all Value entities.
     *
     * @Route("/", name="admin_notebook_value")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminNotebookBundle:Value')->findBy(
            [],
            ['position' => 'ASC']
        );
        
        return ['entities' => $entities];
    }
    /**
     * Creates a new Value entity.
     *
     * @Route("/", name="admin_notebook_value_create")
     * @Method("POST")
     * @Template("AdminNotebookBundle:Value:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Value();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $req = $this->getRequest()->request->all();
            $subid = $req['admin_notebookbundle_value']['property'];    
                    
            $em = $this->getDoctrine()->getManager();
            
            $repository = $em->getRepository('AdminNotebookBundle:Value');
            
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
                'Свойство добавлено!'
            );
            
            return $this->redirect($this->generateUrl('admin_notebook_value_newsub', ['id' => $subid]));
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView()
        ];
    }

    /**
     * Creates a form to create a Value entity.
     *
     * @param Value $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Value $entity, $id = 0)
    {
        $form = $this->createForm(new ValueType(), $entity, [
            'action' => $this->generateUrl('admin_notebook_value_create'),
            'method' => 'POST',
        ]);

        if ($id) {
           $form->add('property', 'hidden', ['data' => $id]); 
        }
        
        return $form;
    }

    /**
     * Displays a form to create a new Value entity.
     *
     * @Route("/new", name="admin_notebook_value_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Value();
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView()
        ];
    }

    /**
     * Displays a form to edit an existing Value entity.
     *
     * @Route("/{id}/edit", name="admin_notebook_value_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Value')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Value entity.');
        }

        $editForm = $this->createEditForm($entity);
        $imagepath = $entity->getImage();
        
        return [
            'entity'      => $entity,
            'imagepath'   => $imagepath,
            'edit_form'   => $editForm->createView()
        ];
    }

    /**
    * Creates a form to edit a Value entity.
    *
    * @param Value $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Value $entity)
    {
        $form = $this->createForm(new ValueType(), $entity, [
            'action' => $this->generateUrl('admin_notebook_value_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        return $form;
    }
    /**
     * Edits an existing Value entity.
     *
     * @Route("/{id}", name="admin_notebook_value_update")
     * @Method("PUT")
     * @Template("AdminNotebookBundle:Value:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Value')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Value entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->addFlash(
                'notice_success',
                'Данные успешно сохранены!'
            );

            return $this->redirect($this->generateUrl('admin_notebook_value_edit', ['id' => $id]));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ];
    }
 
   
    /**
     * Delete Properties entities.
     * 
     * @Route("/delete/{id}", name="admin_notebook_value_delete")
     * @Template()
     */
    public function deleteAction()
    {
        
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Value')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Value entity.');
        }
        
        $em->remove($entity);
        $em->flush();
        
    }
    
   
    /**
     * Get Properties for sort.
     * 
     * @Route("/sort/", name="admin_notebook_value_sort")
     * @Template()
     */
    public function sortAction()
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('AdminNotebookBundle:Value');
        
        $entities = $repository->findBy(
            [],
            ['position' => 'ASC']);
        
        return ['entities' => $entities];
    }
    
    /**
     * Sort Properties Save.
     * 
     * @Route("/sortsave/", name="admin_notebook_value_sortsave")
     * @Template()
     */
    public function sortSaveAction()
    {
         
        $z = str_replace('ID[]=','',$_POST['order']); 
         
        $newOrder = explode("&",$z);
        $counter = 1;

        $em = $this->getDoctrine()->getManager();
        
        foreach ($newOrder as $recordIDValue) {
            
            $product = $em->getRepository('AdminNotebookBundle:Value')->find(intval($recordIDValue));
            $product->setPosition($counter);
      
            $counter ++;
        }
        
        $em->flush();

        $repository = $em->getRepository('AdminNotebookBundle:Value');

        
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
     * Displays a form to create a new value entity from property.
     * 
     * @Route("/newsub/{id}", name="admin_notebook_value_newsub")
     * @Template()
     */
    public function newSubAction($id = 0)
    {
        $entity = new Value();
        $form   = $this->createCreateForm($entity, $id);
        
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AdminNotebookBundle:Properties');

        $entities = $repository->find($id);
       
        return [
            'form'   => $form->createView(),
            'category'   => $entities->getName()
        ];
        
    }
          
}
