<?php

namespace Admin\FinanceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use Admin\FinanceBundle\Entity\Order;
use Admin\FinanceBundle\Form\OrderType;

/**
 * Order controller.
 *
 * @Route("/admin/order")
 */
class OrderController extends Controller
{

    /**
     * Lists all Order entities.
     *
     * @Route("/", name="admin_order")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AdminFinanceBundle:Order')->findBy(
            [],
            ['id' => 'DESC']
        );
        
        $state = ['Не подтверждён','Подтверждён','Отменён','Доставлено'];

        return ['entities' => $entities, 'states' => $state];
    }
    /**
     * Creates a new Order entity.
     *
     * @Route("/", name="admin_order_create")
     * @Method("POST")
     * @Template("AdminFinanceBundle:Order:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Order();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_order_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Order entity.
     *
     * @param Order $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Order $entity)
    {
        $form = $this->createForm(new OrderType(), $entity, array(
            'action' => $this->generateUrl('admin_order_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Order entity.
     *
     * @Route("/new", name="admin_order_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Order();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Order entity.
     *
     * @Route("/{id}", name="admin_order_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }

        $entitiesOrder = $em->getRepository('AdminFinanceBundle:OrderProduct')->findBy(
            ['order' => $id],
            ['id' => 'DESC']
        );
        
        $state = ['Не подтверждён','Подтверждён','Отменён','Доставлено'];
        
        return [
            'entity' => $entity,
            'orders' => $entitiesOrder,
            'states' => $state
        ];
    }

    /**
     * Displays a form to edit an existing Order entity.
     *
     * @Route("/{id}/edit", name="admin_order_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }

        $editForm = $this->createEditForm($entity);

        $entitiesOrder = $em->getRepository('AdminFinanceBundle:OrderProduct')->findBy(
            ['order' => $id],
            ['id' => 'DESC']
        );
        
        return array(
            'entity'      => $entity,
            'orders'      => $entitiesOrder,
            'form'   => $editForm->createView()
        );
    }

    /**
    * Creates a form to edit a Order entity.
    *
    * @param Order $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Order $entity)
    {
        $form = $this->createForm(new OrderType(), $entity, array(
            'action' => $this->generateUrl('admin_order_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Order entity.
     *
     * @Route("/{id}", name="admin_order_update")
     * @Method("PUT")
     * @Template("AdminFinanceBundle:Order:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->addFlash(
                'notice_success',
                'Данные сохранены!'
            );

            return $this->redirect($this->generateUrl('admin_order'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
 
 
        
    /**
     * Change state order entities.
     * 
     * @Route("/changestate/{id}", name="admin_order_changestate")
     * @Template()
     */
    public function changeStateAction()
    {
     
        $id = $this->getRequest()->get('id'); 
        $state = $this->getRequest()->get('value'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:Order')->find($id);
                   
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Notebook entity.');
        }
       
        $entity->setState($state);
        $em->persist($entity);
        $em->flush();
        
        $state = ['Не подтверждён','Подтверждён','Отменён','Доставлено'];
        
        return [
            'entity' => $entity,
            'states'  => $state
        ];
        
    }
    
            
    /**
     * Delete Order entities.
     * 
     * @Route("/delete/{id}", name="admin_order_delete")
     * @Template()
     */
    public function deleteAction()
    {
     
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:Order')->find($id);
                    
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }
        
        $orderProduct = $em->getRepository('AdminFinanceBundle:OrderProduct')->findBy(['order' => $id]);
        
        foreach ($orderProduct as $val) {
            $entityOrder = $em->getRepository('AdminFinanceBundle:OrderProduct')->find($val->getId());
            $em->remove($entityOrder);
            $em->flush();
        }
        
        $em->remove($entity);
        $em->flush(); 
        
        return[];
    }
    
    /**
     * Latest Orders entities.
     * 
     * @Route("/getlatestorder/{id}", name="admin_order_getlatestorder")
     * @Template()
     */
    public function getLatestOrderAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AdminFinanceBundle:Order')->findBy(
            [],
            ['id' => 'DESC'],
            5
                
        );
        
        $state = ['Не подтверждён','Подтверждён','Отменён','Доставлено'];

        return ['entities' => $entities, 'states' => $state];
    }
    
            
    /**
     * Delete Product entities.
     * 
     * @Route("/{oid}/deleteproduct/{id}", name="admin_order_deleteproduct")
     * @Template()
     */
    public function deleteProductAction()
    {
     
        $id = $this->getRequest()->get('id'); 
        $oid = $this->getRequest()->get('oid'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:OrderProduct')->find($id);
           
        
        $entityOrder = $em->getRepository('AdminFinanceBundle:Order')->find($oid);
        
        $price = $entityOrder->getPrice()-$entity->getNotebook()->getPrice();
        
        $entityOrder->setPrice($price);
        $em->persist($entityOrder);
        $em->flush(); 
        
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }
        
        $em->remove($entity);
        $em->flush(); 
        
        $product = $em->getRepository('AdminFinanceBundle:OrderProduct')->findBy(['order' => $oid]);
       
        
        
        if(!$product) {
            $order = $em->getRepository('AdminFinanceBundle:Order')->find($oid);
            $em->remove($order);
            $em->flush(); 
        }

        if (!$entityOrder) {
            return [];
        }

        $editForm = $this->createEditForm($entityOrder);

        $entitiesOrder = $em->getRepository('AdminFinanceBundle:OrderProduct')->findBy(
            ['order' => $oid],
            ['id' => 'DESC']
        );
        
        return array(
            'entity'      => $entityOrder,
            'orders'      => $entitiesOrder,
            'form'   => $editForm->createView()
        );
        
    }
    
    /**
     * 
     * @Route("/addtocart/", name="admin_order_addtocart")
     * @Template()
     */
    public function addToCartAction(Request $request)
    {
        $id = $request->request->get('data');
        
        $session = new Session();
      
        if ($session->get('price')) {
            
            $pattern = "&".$session->get('price')."&";
            
            $count = substr_count($pattern,"&".$id."&");
            
            if (!$count) {
                $notelist = $session->get('price').'&'.$id;
                $session->set('price',$notelist);
            }  
            
        } else {
            $session->set('price',$id);
        }
                    
        $listid = $session->get('price');
        
        $ordersid = explode("&",$listid);
        
        $em = $this->getDoctrine()->getManager();
        
        $repo = $em->getRepository('AdminNotebookBundle:Notebook');

        $query = $repo->createQueryBuilder('n');
        $query->select("sum(n.price) as summ");
        $query->addSelect("count(n.id) as coun");
        $query->where("n.id IN(:nid)");
        $query->setParameter('nid', array_values($ordersid));

        $product =  $query->getQuery()->getResult();
       
        $session->set('countproduct',$product[0]['coun']);
        $session->set('summproduct',$product[0]['summ']);
        
        return[];
        
    }
}
