<?php

namespace Frontend\StoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Notebook controller.
 *
 * @Route("/notebook")
 */
class NotebookController extends Controller
{
    /**
     * Notebook list.
     *
     * @Route("/{slug}", name="notebook", defaults={"slug" = ""})
     * @Template()
     */
    public function notebookAction(Request $request, $slug)
    {
        $session = new Session();
        
        if($slug) {
            
            $em = $this->getDoctrine()->getManager();
            
            $repo = $em->getRepository('AdminNotebookBundle:Value');
            $query = $repo->createQueryBuilder('v');
            $query->select('v.id as id');
            $query->where("v.value LIKE :slug");
            $query->setParameter('slug', '%'.$slug.'%');
            
            $entities = $query->getQuery()->getResult();
            
            $session->set('filter', $entities[0]['id'].'=0');
            
            $repos = $em->getRepository('AdminNotebookBundle:PropertiesValue');

            $id = $entities[0]['id'];

            $querynote = $repos->createQueryBuilder('p');
            $querynote->select('IDENTITY(p.notebook) as id');
            $querynote->where("p.value = $id");
            $notebookListId = $querynote->getQuery()->getResult();

        } else {
            $session->set('filter', false);
            $notebookListId = false;
        }
        
        $pricemin = round($this->minPrice());
        $pricemax = round($this->maxPrice());
        
        $view = ($session->get('view')) ? : 'table';
 
        return ['view' => $view, 'slug' => $slug, 'pricemin' => $pricemin, 'pricemax' => $pricemax, 'notebookListId' => $notebookListId];
    }
    
    /**
     * Selected filters notebook
     * 
     * @Route("/selectfilter/", name="notebook_selectfilter")
     * @Template()
     */
    public function selectFilterAction(Request $request)
    {
     
        $data = explode("&",$request->request->get('data'));
        
        $session = new Session();
        
        if ($request->request->get('pricemin')) {
            $pricemin = $request->request->get('pricemin');
            $session->set('pricemin', $pricemin);
        } else {
            $pricemin = $this->minPrice();
        }
        
        if ($request->request->get('pricemax')) {
            $pricemax = $request->request->get('pricemax');
            $session->set('pricemax', $pricemax);
        } else {
            $pricemax = $this->maxPrice();
        }
        
       
         
        $em = $this->getDoctrine()->getManager();
        
        $option = $this->getOption($request);
         
        if ($data[0]) {
            
            $repo = $em->getRepository('AdminNotebookBundle:PropertiesValue');
            $query = $repo->createQueryBuilder('p');
            
            $query->select('IDENTITY(p.notebook) as id');
            
            if (count($data) > 1) {
                
                $idNoteList = $this->getListNotebookId($data);
                
                $query->where("p.notebook IN(:notebid)");
                $query->setParameter('notebid', array_values($idNoteList));
            } else {
                $query->where("p.value IN(:filterid)");
                $query->setParameter('filterid', array_values($data));
            }
            
            $query->groupBy('p.notebook');
            
            $notebList = $query->getQuery()->getResult();
 
            $session->set('filter', $request->request->get('data'));
            
            $entities = $this->createCatalog($option['field'], $option['order'], $notebList, $pricemin, $pricemax);
            
        } else {
            
            $repo = $em->getRepository('AdminNotebookBundle:Notebook');
            $query = $repo->createQueryBuilder('n');
            $query->select('n.id as id');
            $query->where('n.price BETWEEN :min AND :max');
            $query->setParameter('min', $pricemin);
            $query->setParameter('max', $pricemax);
            $notebList =  $query->getQuery()->getResult();
            
            $session->remove('filter');
            $entities = $this->createCatalog($option['field'], $option['order'],null ,$pricemin ,$pricemax);
        } 
        
        $entitiesf = $this->selectedFilter();
     
        $viewList =  $this->renderView('FrontendStoreBundle:Notebook:'.$option['view'].'.html.twig', [
            'entities' => $entities,
            'entitiesf' => $entitiesf
        ]);

        
        $viewFilter = $this->filterListAction($request, true, $notebList);
      
        $priceslider = $this->updatePriceSlider($pricemin, $pricemax);
         
        $datajson = json_encode( array('viewlist'=>$viewList,'viewfilter'=>$viewFilter, 'priceslider' => $priceslider) );
        $headers = array( 'Content-type' => 'application-json; charset=utf8' );
        $responce = new Response( $datajson, 200, $headers );
        
        return $responce;
    }
    
    /**
     * Filter list.
     *
     * @Route("/filterlist/", name="notebook_filterlist")
     * @Template()
     */
    public function filterListAction(Request $request, $json = false, $notebookListId = false)
    {
        $em = $this->getDoctrine()->getManager();
        
        $properties = $em->getRepository('AdminNotebookBundle:Value')->findBy(
            [],
            ['position' => 'ASC']
        );
        
        $repo = $em->getRepository('AdminNotebookBundle:PropertiesValue');
        $query = $repo->createQueryBuilder('p');
        $query->select('COUNT(p.value) as notebooks');
        $query->addSelect('IDENTITY(p.value) as id');
        
        if($notebookListId) {
            
            $notebookListId = array_map(function($x){ return $x['id']; }, $notebookListId);
            
            $query->where("IDENTITY(p.notebook) IN(:notebookid)");
            $query->setParameter('notebookid', array_values($notebookListId));
        }
        
        $query->groupBy('p.value');

        $queryd =  $query->getQuery()->getResult();
                
        foreach($queryd as $val) {
            $countn[$val['id']] = $val['notebooks'];
        }
        
        $entitiesf = $this->selectedFilter();
        
        if ($entitiesf) {
            foreach ($entitiesf as $val) {
                $selectedfilter[$val->getId()] = 1;
            }
        } else {
            $selectedfilter = false;
        }
        
        foreach ($properties as $value) {
            
            $count = (isset($countn[$value->getId()]))?$countn[$value->getId()]:0;
            
            $entities[$value->getProperty()->getId()][] = [
                    'id' => $value->getId(),
                    'value' => $value->getValue(),
                    'count' => $count
                ];
        }

        
        if ($json) {
            return $this->renderView('FrontendStoreBundle:Notebook:filterlist.html.twig', [
                'entities' => $entities,
                'selectedfilter' => $selectedfilter 
            ]); 
        } else {
        
            return $this->render('FrontendStoreBundle:Notebook:filterlist.html.twig', [
                'entities' => $entities,
                'selectedfilter' => $selectedfilter 
            ]);
        }
    }
    
    /**
     * Sort notebook.
     *
     * @Route("/sort/", name="notebook_sort")
     * @Template()
     */
    public function changeSortAction(Request $request)
    {
        $data = $request->request->get('data');
        
        $session = new Session();
        
        $session->set('field', $data['sort_field']);
        $session->set('order', $data['sort_order']);
        
        return $this->catalogAction($request);  
        
    }
    
    /**
     * Change view catelog notebook.
     *
     * @Route("/changeview/", name="notebook_changeview")
     * @Template()
     */
    public function changeViewAction(Request $request)
    {
        $newview = $request->request->get('data');
        
        $session = new Session();
        
        $session->remove('view');
        $session->set('view', $newview);
        
        return $this->catalogAction($request);         
    }
    
    /**
     * Notebook catalog.
     *
     * @Route("/catalog/", name="notebook_catalog")
     * @Template()
     */
    public function catalogAction(Request $request)
    {
        $session = new Session();
        
        $option = $this->getOption($request);
       
        $em = $this->getDoctrine()->getManager();

        if ($session->get('filter')) {

            $data = explode("&",$session->get('filter'));
            
            $repo = $em->getRepository('AdminNotebookBundle:PropertiesValue');
            $query = $repo->createQueryBuilder('p');
            $query->select('IDENTITY(p.notebook) as id');
            
            if (count($data) > 1) {
                
                $idNoteList = $this->getListNotebookId($data);
                $query->andWhere("p.notebook IN(:notebid)");
                $query->setParameter('notebid', array_values($idNoteList));
                
            } else {
                
                $query->where("p.value IN(:filterid)");
                $query->setParameter('filterid', array_values($data));
                $query->groupBy('p.notebook');
            }

            $filter = $query->getQuery()->getResult();

        } else {
            $filter = false;
        }
        
     
        $pricemin = $this->minPrice();
        $pricemax = $this->maxPrice();
         
       
        $entities = $this->createCatalog($option['field'], $option['order'], $filter ,$pricemin ,$pricemax);
        
        $entitiesf = $this->selectedFilter();
        
        return $this->render('FrontendStoreBundle:Notebook:'.$option['view'].'.html.twig', [
            'entities' => $entities,
            'entitiesf' => $entitiesf
            ]);
    }
    
    /**
     * 
     * Initialization options.
     *  
     */
    public function getOption(Request $request)
    {
        
        $session = new Session();
        
        if (!$session->get('field')) {
            $session->set('field', 'created');
        }
        
        if (!$session->get('order')) {
            $session->set('order', 'DESC');
        }
        
        if (!$session->get('view')) {
            $session->set('view', 'table');
        }
        
        
        $option['field'] = ($session->get('field')) ? : 'created';
        $option['order'] = ($session->get('order')) ? : 'DESC';
        $option['view']  = ($session->get('view'))  ? : 'table';
        
        return $option;
    }
    
    /**
     * Create catalog notebook
     */
    public function createCatalog($field = null, $order = null, $filter = null, $minprice, $maxprice)
     {
        $em = $this->getDoctrine()->getManager();
         
        $repo = $em->getRepository('AdminNotebookBundle:Notebook');
        $query = $repo->createQueryBuilder('n');
        
        $query->where('n.price BETWEEN :min AND :max');
        $query->setParameter('min', $minprice);
        $query->setParameter('max', $maxprice);
        
        if ($filter) {
            
            $query->andwhere("n.id IN(:notebookid)");
            $query->setParameter('notebookid', array_values($filter));
        }
       
        $query->orderBy("n.$field",$order);
 
        $entities =  $query->getQuery()->getResult();
        
        
        
        return $entities;
    }
    
    /**
     * Selected filter list
     *  
     */
    public function selectedFilter()
    {
        
        $session = new Session();
        
        $data = $session->get('filter');
        
        if ($data) {
            
            $filter = explode("&",$data);

            $em = $this->getDoctrine()->getManager();

            $repo = $em->getRepository('AdminNotebookBundle:Value');

            $query = $repo->createQueryBuilder('v');
            $query->where("v.id IN(:vid)");
            $query->setParameter('vid', array_values($filter));

            $entities =  $query->getQuery()->getResult();
            
        } else {
            
            $entities = false;
        }
        
        return $entities;
    }
    
    /**
     * Notebook show page.
     * 
     * @Route("/{slug}/p{id}/", name="notebook_show" , requirements={"id"="\d+"} ,defaults={"slug"="notebook"})
     * @Template()
     */
    public function showAction($id)
    {
 
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AdminNotebookBundle:Notebook')->find($id);
        
        $entityImage = $em->getRepository('AdminNotebookBundle:Image')->findBy(
            ['notebook' => $id]
        );
        
        return ['entity' => $entities, 'entityimage' => $entityImage];
    }
    

    /**
     * 
     *  Price min notebook
     */
    private function minPrice()
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('AdminNotebookBundle:Notebook');

        $query = $repo->createQueryBuilder('n');
        $query->select('n, MIN(n.price) AS min_price');

        $entities =  $query->getQuery()->getResult();
        
        return $entities[0]['min_price'];
    }
    
    /**
     * 
     *  Price max notebook
     */
    private function maxPrice()
    {
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository('AdminNotebookBundle:Notebook');

        $query = $repo->createQueryBuilder('n');
        $query->select('n, MAX(n.price) AS max_price');

        $entities =  $query->getQuery()->getResult();
        
        return $entities[0]['max_price'];
    }
    
    /**
     *  Get Notebook list id
     */
    private function getListNotebookId($data)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AdminNotebookBundle:PropertiesValue');

        $firstId = str_replace('=0','',$data[0]);

        $querynote = $repo->createQueryBuilder('p');
        $querynote->select('IDENTITY(p.notebook) as id');

        $querynote->where("p.value = $firstId");
        $nList = $querynote->getQuery()->getResult();

        $nList = array_map(function($x){ return $x['id']; }, $nList);
        
        unset($data[0]);
        
        foreach ($data as $value) {
            
            $query = $repo->createQueryBuilder('p');
            $query->select('IDENTITY(p.notebook) as id');
            $query->where("p.notebook IN(:notesid)");
            $query->setParameter('notesid', array_values($nList));

            $id = str_replace('=0','',$value);

            $query->andWhere("p.value = $id");
            $nList = $query->getQuery()->getResult();

            $nList = array_map(function($x){ return $x['id']; }, $nList);
        }
        
        return $nList;        
       
    }
    
    /**
     * Price slider filter
     *
     * @Route("/priceslider/", name="notebook_filterprice")
     * @Template()
     */
    public function priceSliderAction()
    {
        $pricemin = round($this->minPrice());
        $pricemax = round($this->maxPrice());
        
        return [
            'pricemin' => $pricemin,
            'pricemax' => $pricemax
        ];
    }
    
    /**
     * Update price slider
     */
    private function updatePriceSlider($pricemin = false, $pricemax = false)
    {
        
        $pricemin = ($pricemin) ?  : $this->minPrice();
        $pricemax = ($pricemax) ? :  $this->maxPrice();
        
         
        return $this->renderView('FrontendStoreBundle:Notebook:priceSlider.html.twig', [
            'pricemin' => round($pricemin),
            'pricemax' => round($pricemax)
        ]);
    }
    
}