<?php

namespace Qwer\LottoDocumentsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Qwer\LottoDocumentsBundle\Entity\Bet;
use Qwer\LottoDocumentsBundle\Form\BetType;
use Qwer\LottoDocumentsBundle\Form\BodyType;
use Qwer\LottoDocumentsBundle\Event\BetRequestEvent;

/**
 * Bet controller.
 *
 */
class BetController extends Controller
{

    /**
     * Lists all Bet entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('QwerLottoDocumentsBundle:Bet')->findAll();

        return $this->render('QwerLottoDocumentsBundle:Bet:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Bet entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Bet();
        $form = $this->createForm(new BetType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bet_show', array('id' => $entity->getId())));
        }

        return $this->render('QwerLottoDocumentsBundle:Bet:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Bet entity.
     *
     */
    public function newAction()
    {
        $entity = new Bet();
        $form = $this->createForm(new BetType(), $entity);

        return $this->render('QwerLottoDocumentsBundle:Bet:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Bet entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QwerLottoDocumentsBundle:Bet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('QwerLottoDocumentsBundle:Bet:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Displays a form to edit an existing Bet entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QwerLottoDocumentsBundle:Bet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bet entity.');
        }

        $editForm = $this->createForm(new BetType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('QwerLottoDocumentsBundle:Bet:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Bet entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QwerLottoDocumentsBundle:Bet')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Bet entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new BetType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $event = new \Itc\DocumentsBundle\Event\DocumentEvent($entity);

            $dispatcher = $this->get("event_dispatcher");
            $dispatcher->dispatch("update.document.event", $event);


            return $this->redirect($this->generateUrl('bet_edit', array('id' => $id)));
        }

        return $this->render('QwerLottoDocumentsBundle:Bet:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Bet entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('QwerLottoDocumentsBundle:Bet')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Bet entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bet'));
    }

    /**
     * Creates a form to delete a Bet entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
        ->add('id', 'hidden')
        ->getForm()
        ;
    }

    public function betRequestAction(Rerquest $request)
    {
        $body = new Body();
        $form = $this->createForm(new BodyType, $body);

        $form->bindRequest($request);

        if ($form->isValid()) {
            $dispatcher = $this->getEventDispatcher();
            
            $event = new BetRequestEvent();
            $dispatcher->dispatch("bet.request.event", $event);
            
            $this->get('session')->setFlash(
                'notice',
                'request was processed!'
            );
        }
        
        return $this->redirect($this->generateUrl('bet_index'));
    }

    /**
     * 
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    private function getEventDispatcher()
    {
        $dispatcher = $this->get("event_dispatcher");
        
        return $dispatcher;
    }

}
