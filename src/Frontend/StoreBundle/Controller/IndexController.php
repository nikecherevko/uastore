<?php

namespace Frontend\StoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use Admin\FinanceBundle\Entity\Order;
use Admin\FinanceBundle\Entity\OrderProduct;
use Admin\ContactBundle\Entity\Callme;


/**
 * Index controller.
 *
 * @Route("/")
 */
class IndexController extends Controller
{
    /**
     * Homepage.
     *
     * @Route("", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AdminNotebookBundle:Notebook')->findBy(
            [],
            ['price' => 'DESC'],
            10
        );
        
        return[
            'entities' => $entities
        ];
    }
    
    /**
     * Article page.
     *
     * @Route("/article/{id}/{slug}", name="article", requirements={"id"="\d+","slug"="[a-zA-Z1-9\-_\/]+"})
     * @Template()
     */
    public function articleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AdminInfoBundle:Page')->find($id);
        
        return ['entities' => $entities];
    }
    
    /**
     * Menu.
     *
     * @Route("/menu/", name="menu")
     * @Template()
     */
    public function menuAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AdminNotebookBundle:Value')->findBy(['property'=>1]);
        
        return ['entities' => $entities];
    }
    
    /**
     * Cart.
     *
     * @Route("/cart/", name="cart")
     * @Template()
     */
    public function cartAction(Request $request)
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
        
        $entities = $em->getRepository('AdminNotebookBundle:Notebook')->find($id);
        
        $viewList =  $this->renderView('FrontendStoreBundle:Index:cart.html.twig', [
            'entity' => $entities,
            'product' => $product[0]
        ]);
        
        $cart = $this->updateCart($product[0]['coun'],$product[0]['summ']);
        
        $session->set('countproduct',$product[0]['coun']);
        $session->set('summproduct',$product[0]['summ']);
       
        $datajson = json_encode( array('viewlist'=>$viewList,'cart'=>$cart) );
        $headers = array( 'Content-type' => 'application-json; charset=utf8' );
        $responce = new Response( $datajson, 200, $headers );
        
        return $responce;
    } 
   
    /**
     * Order
     * 
     * @Route("/order/", name="order")
     * @Template("")
     */
    public function orderAction(Request $request)
    {
        $session = new Session();
        
        $em = $this->getDoctrine()->getManager();
        $listid = $session->get('price');
        $tel = $request->request->get('data');
        
        if ($listid and $tel) {
            
            $ordersid = explode("&",$listid);
            
            $repo = $em->getRepository('AdminNotebookBundle:Notebook');

            $query = $repo->createQueryBuilder('n');
            $query->select("sum(n.price) as summ");
            $query->addSelect("count(n.id) as coun");
            $query->where("n.id IN(:nid)");
            $query->setParameter('nid', array_values($ordersid));

            $product =  $query->getQuery()->getResult();
             
            $repoorder = $em->getRepository('AdminFinanceBundle:Payment')->find(2);
            
            $entity = new Order();
            $entity->setPayment($repoorder);
            $entity->setPrice($product[0]['summ']);
            $entity->setTelephone($tel);
            $em->persist($entity);
            $em->flush();
            
            $category = $em->getRepository('AdminCategoryBundle:Category')->find(4);
            $order = $em->getRepository('AdminFinanceBundle:Order')->find($entity->getId());
            
            
            foreach ($ordersid as $val) {
              
                $notebook = $em->getRepository('AdminNotebookBundle:Notebook')->find($val);
                
                $entityop = new OrderProduct();
                $entityop->setOrder($order);
                $entityop->setCategory($category);
                $entityop->setNotebook($notebook);
                $em->persist($entityop);
                $em->flush();
            }
            
            $session->set('price',false);
            
            $data = 'OK';
            
        } else {
            $data = 'Ошибка! Нету товаров.';
        }
        
        $viewList =  $this->renderView('FrontendStoreBundle:Index:order.html.twig', [
            'data' => $data             
        ]);
        
        $cart = $this->updateCart();
        
        $datajson = json_encode( array('viewlist'=>$viewList,'cart'=>$cart) );
        $headers = array( 'Content-type' => 'application-json; charset=utf8' );
        $responce = new Response( $datajson, 200, $headers );
        
        return $responce;
        
        
    }
    
    /**
     * Call me
     * 
     * @Route("/callme/", name="callme")
     * @Template()
     */
    public function callMeAction(Request $request)
    {
        $tel = $request->request->get('data');
        
        $em = $this->getDoctrine()->getManager();
         
        $entity = new Callme();
        $entity->setTelephone('+38 '.$tel);
        $em->persist($entity);
        $em->flush();
        
        return [];
    }
    
    /**
     * Buy one click
     * 
     * @Route("/buyoneclick/", name="buyoneclick")
     * @Template()
     */
    public function buyOneClickAction(Request $request)
    {
        $session = new Session();
        
        $id = $request->request->get('id');
        $tel = $request->request->get('tel');
        $addressorder = $request->request->get('address');
        $fioorder = $request->request->get('fio');
        
        
        $em = $this->getDoctrine()->getManager();
         
        $repoorder = $em->getRepository('AdminFinanceBundle:Payment')->find(2);
        $category = $em->getRepository('AdminCategoryBundle:Category')->find(4);
        
        if ($id === 'fast') {
            
            $listid = $session->get('price');

            if ($listid and $tel) {

                $ordersid = explode("&",$listid);

                $repo = $em->getRepository('AdminNotebookBundle:Notebook');

                $query = $repo->createQueryBuilder('n');
                $query->select("sum(n.price) as summ");
                $query->addSelect("count(n.id) as coun");
                $query->where("n.id IN(:nid)");
                $query->setParameter('nid', array_values($ordersid));

                $product =  $query->getQuery()->getResult();
                
                $entity = new Order();
                $entity->setPayment($repoorder);
                $entity->setPrice($product[0]['summ']);
                $entity->setTelephone($tel);
                if($addressorder) {
                    $entity->setAddress($addressorder);
                }
                if($fioorder) {
                    $entity->setCustomer($fioorder);
                }
                if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
                   $entity->setState(1);
                }
                $em->persist($entity);
                $em->flush();

                $order = $em->getRepository('AdminFinanceBundle:Order')->find($entity->getId());

                foreach ($ordersid as $val) {

                    $notebook = $em->getRepository('AdminNotebookBundle:Notebook')->find($val);

                    $entityop = new OrderProduct();
                    $entityop->setOrder($order);
                    $entityop->setCategory($category);
                    $entityop->setNotebook($notebook);
                    $em->persist($entityop);
                    $em->flush();
                }    
            } else {
                return false;
            }    
            
        } else {
            $entities = $em->getRepository('AdminNotebookBundle:Notebook')->find($id);

            $entity = new Order();
            $entity->setPayment($repoorder);
            $entity->setPrice($entities->getPrice());
            $entity->setTelephone('+38 '.$tel);
            $em->persist($entity);
            $em->flush();

            $order = $em->getRepository('AdminFinanceBundle:Order')->find($entity->getId());

            $entityop = new OrderProduct();
            $entityop->setOrder($order);
            $entityop->setCategory($category);
            $entityop->setNotebook($entities);
            $em->persist($entityop);
            $em->flush();

            
        }

        $session->set('price',false);
        
        return [];
    }
     
    /**
     * Search 
     * 
     * @Route("/search/", name="search")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        $search = $request->request->get('search');
        
        $em = $this->getDoctrine()->getManager();
        
        $repo = $em->getRepository("AdminNotebookBundle:Notebook");

        $query = $repo->createQueryBuilder('n');
        $query->where("n.manufacturer LIKE :search  OR n.model LIKE :search OR n.cpu_series LIKE :search " );
        $query->setParameter('search', '%'.$search.'%');
            
        $searchentitites = $query->getQuery()->getResult();
        
        $entities = ($searchentitites) ?:false;
        
        return ['entities' => $entities];
        
    }
    
    /**
     * 
     * My cart
     * 
     * @Route("/mycart/", name="mycart")
     * @Template()
     * 
     */
    public function myCartAction(Request $request)
    {
        $session = new Session();
        
        $em = $this->getDoctrine()->getManager();
        
        $listid = $session->get('price');
        
        if ($listid) {
            
            $count = $session->get('countproduct');
            $sum   = $session->get('summproduct');
            
            $ordersid = explode("&",$listid);
            
            $repo = $em->getRepository('AdminNotebookBundle:Notebook');

            $query = $repo->createQueryBuilder('n');
            $query->where("n.id IN(:nid)");
            $query->setParameter('nid', array_values($ordersid));
            $entities =  $query->getQuery()->getResult();
            
        } else {
            $count = $entities = $sum = false;
        }
        
        return [
            'entities' => $entities,
            'count' => $count,
            'sum'   =>  $sum
        ];
    }
    
    /**
     * View cart
     * 
     * @Route("/viewcart/", name="viewcart")
     * @Template()
     */
    public function viewCartAction()
    {
        $session = new Session();
        
        $listid = $session->get('price');
        
        if ($listid) {
            $count = $session->get('countproduct');
            $sum   = $session->get('summproduct');
            
            return [
                'count' => $count,
                'sum'   =>  $sum
            ];
        } else {
            return [
                'count' => 0
            ];
        }
    }
    
    /**
     * Update cart
     */
    private function updateCart($count = 0,$sum = 0)
    {
        return $this->renderView('FrontendStoreBundle:Index:updateCart.html.twig', [
            'count' => $count,
            'sum' => $sum
        ]);
    }
    
    /**
     * Delete product cart
     * 
     * @Route("/delproduct/", name="delproduct")
     * @Template()
     */
    public function delProductAction(Request $request)
    {
        $session = new Session();
        
        $id = $request->request->get('id');
        
        $em = $this->getDoctrine()->getManager();
        
        $listid = $session->get('price');
        
        if ($listid and $id) {
            
            
           
            
            $pattern = "&".$listid."&";
            
            $delproduct = str_replace('&'.$id.'&','&',$pattern);
            
            $delproduct = substr($delproduct, 1, -1);
            
            $findid = preg_match_all('|\d+|', $delproduct); 
            
            if ($findid) {
                
                $session->set('price',$delproduct);
                $ordersid = explode("&",$delproduct);
                
                $result = array_filter($ordersid);
                        
                $repo = $em->getRepository('AdminNotebookBundle:Notebook');

                $query = $repo->createQueryBuilder('n');
                $query->select("sum(n.price) as summ");
                $query->addSelect("count(n.id) as coun");
                $query->where("n.id IN(:nid)");
                $query->setParameter('nid', array_values($result));

                $product =  $query->getQuery()->getResult();

                $session->set('countproduct',$product[0]['coun']);
                $session->set('summproduct',$product[0]['summ']);
                
                $count = true;
                 
            } else {
                $session->set('price',false);
                $session->set('countproduct',false);
                $session->set('summproduct',false);
                
                $count = false;
            }
            
            
        } else {
            $count = $viewList = $sum = false;
        }
        
        if($count) {
            $viewList =  $this->renderView('FrontendStoreBundle:Index:delProduct.html.twig', [
                'count' => $product[0]['coun'],             
                'sum' => $product[0]['summ']            
            ]);
            
            $sum = $this->totalSum($product[0]['summ']);
        } else {
            $viewList =  $this->renderView('FrontendStoreBundle:Index:delProduct.html.twig', [
                'count' => false,             
            ]);
            
            $sum = false;
            
        }
        
        $datajson = json_encode( array('viewlist'=>$viewList,'sum'=>$sum, 'count' => $count) );
        $headers = array( 'Content-type' => 'application-json; charset=utf8' );
        $responce = new Response( $datajson, 200, $headers );
        
        return $responce;
        
    }
    
    /**
     * Total sum
     */
    private function totalSum($sum)
    {
        return $this->renderView('FrontendStoreBundle:Index:totalSum.html.twig', [
            'sum' => $sum
        ]);
    }
}