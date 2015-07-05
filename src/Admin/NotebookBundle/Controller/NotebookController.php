<?php

namespace Admin\NotebookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\NotebookBundle\Entity\Notebook;
use Admin\NotebookBundle\Entity\Image;
use Admin\NotebookBundle\Entity\PropertiesValue;
use Admin\NotebookBundle\Form\NotebookType;

/**
 * Notebook controller.
 *
 * @Route("/admin/notebook")
 */
class NotebookController extends Controller
{

    /**
     * Lists all Notebook entities.
     *
     * @Route("/", name="admin_notebook")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminNotebookBundle:Notebook')->findBy(
            [],
            ['id' => 'DESC']
        );
       
        return ['entities' => $entities];
    }
    /**
     * Creates a new Notebook entity.
     *
     * @Route("/", name="admin_notebook_create")
     * @Method("POST")
     * @Template("AdminNotebookBundle:Notebook:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Notebook();
        $form = $this->createCreateForm($entity, 4);
        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
           
            $nextId = $entity->getId();
            
            $notebook_id = $em->getRepository('AdminNotebookBundle:Notebook')->findOneById($nextId);
            
            $photos = $request->files->get('photo');
            
            $i = 0;
            
            foreach ($photos as $val) {
                
                $entityImage = new Image();
                
                $entityImage->setNotebook($notebook_id);
                
                $entityImage->setFile($val);
                if (!$i) {
                    $entityImage->setMain(1);
                }
                $em->persist($entityImage);
                $em->flush();
                $i++;
            }
            
            $entitiesImage = $em->getRepository('AdminNotebookBundle:Image')->findBy(
                ['notebook' => $nextId],
                ['id' => 'ASC'],
                1
            );
            
            $entity->setImage($entitiesImage[0]->getImage());
            
            $filter = array_filter($request->request->get('filtr'));
            
            if ($filter) {
                      
                foreach ($filter as $val) {
                    
                    $propertyValues = new PropertiesValue();
                    $value_id= $em->getRepository('AdminNotebookBundle:Value')->findOneById($val);
                    
                    $propertyValues->setNotebook($notebook_id);
                    $propertyValues->setValue($value_id);
                    
                    $em->persist($propertyValues);
                    $em->flush();
                }
                
            }
            
            $this->addFlash(
                'notice_success',
                'Ноутбук успешно добавлен!'
            );
            return $this->redirect($this->generateUrl('admin_notebook_show', ["id" => $nextId]));
        }

        $entityValue = $this->getDoctrine()->getManager()->getRepository('AdminNotebookBundle:Value')->findAll();
        
        foreach ($entityValue as $value) {
            $values[$value->getProperty()->getId()][] = [
                    'id' => $value->getId(),
                    'value' => $value->getValue()
            ]; 
        }
        
        return [
            'value' => $values,
            'entity' => $entity,
            'form'   => $form->createView()
        ];
    }

    /**
     * Creates a form to create a Notebook entity.
     *
     * @param Notebook $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Notebook $entity, $category_id = 4)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AdminCategoryBundle:Category')->find($category_id);
        
        $form = $this->createForm(new NotebookType(), $entity, [
            'action' => $this->generateUrl('admin_notebook_create'),
            'method' => 'POST',
            'attr' => ['enctype' => 'multipart/form-data'],
            'em' => $this->getDoctrine()->getManager(),
            'category_id' => $category,

        ]);

        return $form;
    }

    /**
     * Displays a form to create a new Notebook entity.
     *
     * @Route("/new", name="admin_notebook_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Notebook();
        $form   = $this->createCreateForm($entity, 4);
        
        $em = $this->getDoctrine()->getManager();

        $entityValue = $em->getRepository('AdminNotebookBundle:Value')->findAll();
        
        foreach ($entityValue as $value) {
            $values[$value->getProperty()->getId()][] = [
                    'id' => $value->getId(),
                    'value' => $value->getValue()
            ]; 
        }

      
        return [
            'value' => $values,
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
        
    }

    /**
     * Finds and displays a Notebook entity.
     *
     * @Route("/{id}", name="admin_notebook_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Notebook')->find($id);
        
        $entityImage = $em->getRepository('AdminNotebookBundle:Image')->findBy(
            ['notebook' => $id]
        );
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notebook entity.');
        }

        return [
            'entity'      => $entity,
            'entityimage'      => $entityImage,
        ];
    }

    /**
     * Displays a form to edit an existing Notebook entity.
     *
     * @Route("/{id}/edit", name="admin_notebook_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Notebook')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notebook entity.');
        }
  
        $editForm = $this->createEditForm($entity);
        
        $entityValue = $em->getRepository('AdminNotebookBundle:Value')->findAll();
        
        $entityPropVal = $em->getRepository('AdminNotebookBundle:PropertiesValue')->findBy(
            ['notebook' => $id]
        );
        
        $entityImage = $em->getRepository('AdminNotebookBundle:Image')->findBy(
            ['notebook' => $id]
        );
        
        foreach ($entityPropVal as $val) {
            $propvalue[] = $val->getValue()->getId();
        }
        
        foreach ($entityValue as $value) {
            
            if (in_array($value->getId(), $propvalue)) {
                $active = 1;
            } else {
                $active = 0;
            }
            
            $values[$value->getProperty()->getId()][] = [
                    'id' => $value->getId(),
                    'value' => $value->getValue(),
                    'active' => $active
            ]; 
        }
        
        return [
            'value'  => $values,
            'entity' => $entity,
            'images' => $entityImage,
            'form'   => $editForm->createView()
        ];
    }

    /**
    * Creates a form to edit a Notebook entity.
    *
    * @param Notebook $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Notebook $entity)
    {
        $form = $this->createForm(new NotebookType(), $entity, [
            'action' => $this->generateUrl('admin_notebook_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
            'attr' => ['enctype' => 'multipart/form-data'],
            'category_id' => $entity->getCategory(),
            'em' => $this->getDoctrine()->getManager(),
            
        ]);
 
        return $form;
    }
    
    /**
    * Creates a form to addmodification a Notebook entity.
    *
    * @param Notebook $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createModificationForm(Notebook $entity)
    {
        $form = $this->createForm(new NotebookType(), $entity, [
            'action' => $this->generateUrl('admin_notebook_create'),
            'method' => 'POST',
            'attr' => ['enctype' => 'multipart/form-data'],
            'category_id' => $entity->getCategory(),
            'em' => $this->getDoctrine()->getManager(),
            
        ]);

        return $form;
    }
    
    
    /**
     * Edits an existing Notebook entity.
     *
     * @Route("/{id}", name="admin_notebook_update")
     * @Method("PUT")
     * @Template("AdminNotebookBundle:Notebook:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Notebook')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notebook entity.');
        }
       
        $editForm = $this->createEditForm($entity);
         
        $editForm->handleRequest($request);
 
        if ($editForm->isValid()) {
            
            $category_id = $em->getRepository('AdminCategoryBundle:Category')->findOneById(4);
            
            $entity->setCategory($category_id);
            
            $em->persist($entity);
            $em->flush();
            
            $notebook_id = $em->getRepository('AdminNotebookBundle:Notebook')->findOneById($id);
            
            $photos = $request->files->get('photo');
             
            $i = 0;
            
            if ($photos[0]) {
                
            $imageMain = $em->getRepository('AdminNotebookBundle:Image')->findBy(
                ['notebook' => $id, 'main' => 1]
            );
                
            foreach ($photos as $val) {

                $entityImage = new Image();
                $entityImage->setNotebook($notebook_id);
                $entityImage->setFile($val);

                if (!$i and !$imageMain) {
                    $entityImage->setMain(1);
                }

                $em->persist($entityImage);
                $em->flush();

                if(!$notebook_id->getImage() && !$i) {
                    
                    $imageMainSel = $em->getRepository('AdminNotebookBundle:Image')->findBy(
                        ['notebook' => $id, 'main' => 1]
                    );
                    
                    $entity->setImage($imageMainSel[0]->getImage());
                    $em->persist($entity);
                    $em->flush();
                    
                }

                $i++;

            }
            }
            
                     
            $filter = array_filter($request->request->get('filtr'));
            
            $entityPrV = $em->getRepository('AdminNotebookBundle:PropertiesValue')->findBy(
                ['notebook' => $id]
            );
                    
            foreach ($entityPrV as $valDel) {
                
                $delpv = $em->getRepository('AdminNotebookBundle:PropertiesValue')->find($valDel->getId());
                $em->remove($delpv);
                $em->flush();
            }
            
 
            if ($filter) {
                      
                foreach ($filter as $val) {
                    
                    $propertyValues = new PropertiesValue();
                    $value_id= $em->getRepository('AdminNotebookBundle:Value')->findOneById($val);
                    
                    $propertyValues->setNotebook($notebook_id);
                    $propertyValues->setValue($value_id);
                    
                    $em->persist($propertyValues);
                    $em->flush();
                }
                
            }
            
            $this->addFlash(
                'notice_success',
                'Данные успешно сохранены!'
            );

            return $this->redirect($this->generateUrl('admin_notebook_show', ['id' => $id]));
        }

        $entityValue = $this->getDoctrine()->getManager()->getRepository('AdminNotebookBundle:Value')->findAll();
        
        foreach ($entityValue as $value) {
            $values[$value->getProperty()->getId()][] = [
                    'id' => $value->getId(),
                    'value' => $value->getValue()
            ]; 
        }
        
        return [
            'value' => $values,
            'entity' => $entity,
            'form'   => $editForm->createView()
        ];
    }
    
    /**
     * Delete Properties entities.
     * 
     * @Route("/delete/{id}", name="admin_notebook_delete")
     */
    public function deleteAction()
    {
     
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Notebook')->find($id);
        $entityImages = $em->getRepository('AdminNotebookBundle:Image')->findBy(['notebook' => $id]);
        $entityPropertyValues = $em->getRepository('AdminNotebookBundle:PropertiesValue')->findBy(['notebook' => $id]);
                    
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notebook entity.');
        }
        
        foreach ($entityImages as $val) {
            $entityImage = $em->getRepository('AdminNotebookBundle:Image')->find($val->getId());
            $em->remove($entityImage);
            $em->flush();
        }
        
        foreach ($entityPropertyValues as $val) {
            $entityPropertyValue = $em->getRepository('AdminNotebookBundle:PropertiesValue')->find($val->getId());
            $em->remove($entityPropertyValue);
            $em->flush(); 
        }
        
        $em->remove($entity);
        $em->flush();
        
        return $this->redirect($this->generateUrl('admin_notebook'));
        
    }
 
    
    /**
     * Delete Image entities.
     * 
     * @Route("/{nid}/deleteimage/{id}", name="admin_notebook_deleteimage")
     * @Template()
     */
    public function deleteImageAction()
    {
         
        $id = $this->getRequest()->get('id'); 
        $main = $this->getRequest()->get('main'); 
        $noteid = $this->getRequest()->get('nid'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Image')->find($id);
         
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notebook entity.');
        }
        
        $notebookId = $entity->getNotebook();
        
        $em->remove($entity);
        $em->flush();
        
        if ($main) {
            
            $newMain = $em->getRepository('AdminNotebookBundle:Image')->findBy(
                ['notebook' => $notebookId],
                ['id' => 'ASC'],
                1
            );
            
            if($newMain) {
                  
                $notebookImage = $em->getRepository('AdminNotebookBundle:Notebook')->find($notebookId);
                $notebookImage->setImage($newMain[0]->getImage());

                $entityNewMain = $em->getRepository('AdminNotebookBundle:Image')->find($newMain[0]->getId());
                $entityNewMain->setMain(1);
                
                $em->persist($notebookImage);
                $em->persist($entityNewMain);
                $em->flush();
            
            } else {
                $notebookImage = $em->getRepository('AdminNotebookBundle:Notebook')->find($notebookId);
                $notebookImage->setImage(NULL);
 
                $em->persist($notebookImage);
                $em->flush();
            }
        }
        
        $entityImage = $em->getRepository('AdminNotebookBundle:Image')->findBy(
            ['notebook' => $notebookId]
        );
        
        return ['images' => $entityImage, 'noteid' =>$noteid];
        
    }
    
   
    /**
     * Search Notebook entities.
     * 
     * @Route("/search", name="admin_notebook_search")
     * @Template()
     */
    public function searchAction()
    {
        $code = $this->getRequest()->get('code'); 
        $category = $this->getRequest()->get('category'); 
        
        $name = ($category) ? 'заказ' : 'товар';

        $em = $this->getDoctrine()->getManager();

        if (!$category) {
            
            $entity = $em->getRepository('AdminNotebookBundle:Notebook')->findBy(
               ['code' => $code],
               [],
               1
            );
            
            if ($entity) {
                return $this->redirect($this->generateUrl('admin_notebook_show', ['id' => $entity[0]->getId()])); 
            }
        
        } else {
            $entity = $em->getRepository('AdminFinanceBundle:Order')->findBy(
               ['id' => $code],
               [],
               1
            );
            
            if ($entity) {
                return $this->redirect($this->generateUrl('admin_order_show', ['id' => $entity[0]->getId()])); 
            }
        }
        
        return ['code' => $code, 'name' => $name];
    }
    
    /**
     * Displays a form to new modification an existing Notebook entity.
     *
     * @Route("/{id}/newmodification", name="admin_notebook_newmodification")
     * @Method("GET")
     * @Template()
     */
    public function newModificationAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminNotebookBundle:Notebook')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notebook entity.');
        }
  
        $editForm = $this->createModificationForm($entity);
        
        $entityValue = $em->getRepository('AdminNotebookBundle:Value')->findAll();
        
        $entityPropVal = $em->getRepository('AdminNotebookBundle:PropertiesValue')->findBy(
            ['notebook' => $id]
        );
        
        $entityImage = $em->getRepository('AdminNotebookBundle:Image')->findBy(
            ['notebook' => $id]
        );
        
        foreach ($entityPropVal as $val) {
            $propvalue[] = $val->getValue()->getId();
        }
        
        foreach ($entityValue as $value) {
            
            if (in_array($value->getId(), $propvalue)) {
                $active = 1;
            } else {
                $active = 0;
            }
            
            $values[$value->getProperty()->getId()][] = [
                    'id' => $value->getId(),
                    'value' => $value->getValue(),
                    'active' => $active
            ]; 
        }
        
        return [
            'value'  => $values,
            'entity' => $entity,
            'images' => $entityImage,
            'form'   => $editForm->createView()
        ];
    }
    
    /**
     * Lists all Notebook entities for main.
     *
     * @Route("/getlatest/", name="admin_notebook_latest")
     * @Method("GET")
     * @Template()
     */
    public function getLatestAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminNotebookBundle:Notebook')->findBy(
            [],
            ['id' => 'DESC'],
            3
        );
       
        return ['entities' => $entities];
    }
}
