<?php

namespace Qwer\LottoDocumentsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Qwer\LottoDocumentsBundle\Entity\Language;
use Qwer\LottoDocumentsBundle\Form\LanguagesType;

/**
 * Languages controller.
 *
 */
class LanguagesController extends Controller
{
    /**
     * Lists all Languages entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('QwerLottoDocumentsBundle:Language')->findAll();

        return $this->render('QwerLottoDocumentsBundle:Languages:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Language entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Language();
        $form = $this->createForm(new LanguagesType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('languages_show', array('id' => $entity->getId())));
        }

        return $this->render('QwerLottoDocumentsBundle:Languages:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Language entity.
     *
     */
    public function newAction()
    {
        $entity = new Language();
        $form   = $this->createForm(new LanguagesType(), $entity);

        return $this->render('QwerLottoDocumentsBundle:Languages:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Languages entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QwerLottoDocumentsBundle:Language')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Languages entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('QwerLottoDocumentsBundle:Languages:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Languages entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QwerLottoDocumentsBundle:Language')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Languages entity.');
        }

        $editForm = $this->createForm(new LanguagesType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('QwerLottoDocumentsBundle:Languages:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Languages entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('QwerLottoDocumentsBundle:Language')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Languages entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new LanguagesType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('languages_edit', array('id' => $id)));
        }

        return $this->render('QwerLottoDocumentsBundle:Languages:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Languages entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('QwerLottoDocumentsBundle:Language')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Languages entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('languages'));
    }

    /**
     * Creates a form to delete a Languages entity by id.
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
}
