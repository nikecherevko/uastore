<?php

namespace Admin\MenuBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\MenuBundle\Entity\Menu;
use Admin\MenuBundle\Form\MenuType;

/**
 * Menu controller.
 *
 * @Route("/admin/menu")
 */
class MenuController extends Controller
{

    /**
     * Lists all Menu entities.
     *
     * @Route("/", name="admin_menu")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminMenuBundle:Menu')->findAll();

        return ['entities' => $entities];
    }
    /**
     * Creates a new Menu entity.
     *
     * @Route("/", name="admin_menu_create")
     * @Method("POST")
     * @Template("AdminMenuBundle:Menu:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Menu();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository('AdminMenuBundle:Menu');
            $entities = $repository->findBy([], ['position' => 'DESC'],1);
            
            if ($entities) {
                $entity->setPosition($entities[0]->getPosition()+1);
            } else {
                $entity->setPosition(1);
            }
            
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash('notice_success','Пункт меню успешно создан!');
            
            return $this->redirect($this->generateUrl('admin_menu_category'));
        }
        return ['entity' => $entity, 'form'   => $form->createView()];
    }

    /**
     * Creates a form to create a Menu entity.
     *
     * @param Menu $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Menu $entity, $id = 0)
    {
        $form = $this->createForm(new MenuType(), $entity, [
            'action' => $this->generateUrl('admin_menu_create'),
            'method' => 'POST',
        ]);

        if ($id) {
           $form->add('category', 'hidden', ['data' => $id]); 
        }
        
        return $form;
    }

    /**
     * Displays a form to create a new Menu entity.
     *
     * @Route("/new", name="admin_menu_new")
     * @Method("GET")
     * @Template("")
     */
    public function newAction()
    {
        $entity = new Menu();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Menu sub entity.
     * 
     * @Route("/newmenusub/{id}", name="admin_menu_newmenusub")
     * @Template()
     */
    public function newMenuSubAction($id = 0)
    {
        $entity = new Menu();
        $form   = $this->createCreateForm($entity, $id);
        
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AdminMenuBundle:Category');

        $entities = $repository->find($id);
       
        return [
            'form'   => $form->createView(),
            'category'   => $entities->getName()
        ];
        
    }
    
    /**
     * Displays a form to edit an existing Menu entity.
     *
     * @Route("/{id}/edit", name="admin_menu_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMenuBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }

        $editForm = $this->createEditForm($entity);

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a Menu entity.
    *
    * @param Menu $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Menu $entity)
    {
        $form = $this->createForm(new MenuType(), $entity, [
            'action' => $this->generateUrl('admin_menu_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ]);

        return $form;
    }
    /**
     * Edits an existing Menu entity.
     *
     * @Route("/{id}", name="admin_menu_update")
     * @Method("PUT")
     * @Template("AdminMenuBundle:Menu:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminMenuBundle:Menu')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Menu entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->addFlash(
                'notice_success',
                'Данные успешно сохранены!'
            );
            return $this->redirect($this->generateUrl('admin_menu_edit', ['id' => $id]));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }
     
}
