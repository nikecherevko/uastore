<?php

namespace Admin\FinanceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\FinanceBundle\Entity\Payment;
use Admin\FinanceBundle\Form\PaymentType;

/**
 * Payment controller.
 *
 * @Route("/admin/finance/payment")
 */
class PaymentController extends Controller
{

    /**
     * Lists all Payment entities.
     *
     * @Route("/", name="admin_finance_payment")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminFinanceBundle:Payment')->findAll();

        return ['entities' => $entities];
    }
    /**
     * Creates a new Payment entity.
     *
     * @Route("/", name="admin_finance_payment_create")
     * @Method("POST")
     * @Template("AdminFinanceBundle:Payment:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Payment();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash(
                'notice_success',
                'Способ оплаты успешно создан!'
            );

            return $this->redirect($this->generateUrl('admin_finance_payment', ['id' => $entity->getId()]));
        }

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Payment entity.
     *
     * @param Payment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Payment $entity)
    {
        $form = $this->createForm(new PaymentType(), $entity, [
            'action' => $this->generateUrl('admin_finance_payment_create'),
            'method' => 'POST',
        ]);

        return $form;
    }

    /**
     * Displays a form to create a new Payment entity.
     *
     * @Route("/new", name="admin_finance_payment_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Payment();
        $form   = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form'   => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Payment entity.
     *
     * @Route("/{id}", name="admin_finance_payment_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:Payment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Payment entity.');
        }
        
        return [
            'entity'      => $entity
        ];
    }

    /**
     * Displays a form to edit an existing Payment entity.
     *
     * @Route("/{id}/edit", name="admin_finance_payment_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:Payment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Payment entity.');
        }

        $editForm = $this->createEditForm($entity);

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }

    /**
    * Creates a form to edit a Payment entity.
    *
    * @param Payment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Payment $entity)
    {
        $form = $this->createForm(new PaymentType(), $entity, [
            'action' => $this->generateUrl('admin_finance_payment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ]);

        return $form;
    }
    /**
     * Edits an existing Payment entity.
     *
     * @Route("/{id}", name="admin_finance_payment_update")
     * @Method("PUT")
     * @Template("AdminFinanceBundle:Payment:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:Payment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Payment entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            
            $this->addFlash(
                'notice_success',
                'Данные успешно сохранены!'
            );

            return $this->redirect($this->generateUrl('admin_finance_payment_edit', ['id' => $id]));
        }

        return [
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ];
    }
     
    /**
     * Delete Payment entities.
     * 
     * @Route("/delete/{id}", name="admin_finance_payment_delete")
     * @Template()
     */
    public function deleteAction()
    {
        
        $id = $this->getRequest()->get('id'); 
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminFinanceBundle:Payment')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Payment entity.');
        }
        
        $em->remove($entity);
        $em->flush();
    }
}
