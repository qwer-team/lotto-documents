<?php

namespace Qwer\LottoDocumentsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Qwer\LottoDocumentsBundle\Entity\Bet;
use Qwer\LottoDocumentsBundle\Form\BetType;
use Qwer\LottoDocumentsBundle\Form\BodyType;
use Qwer\LottoDocumentsBundle\Event\BetRequestEvent;
use Qwer\LottoDocumentsBundle\Entity\Request\Body;

/**
 * Bet controller.
 *
 */
class BetController extends Controller
{

    /**
     *
     * @var \Doctrine\ORM\EntityRepository 
     */
    private $repo;

    /**
     * Lists all Bet entities.
     *
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getManager();
        $this->repo = $em->getRepository('QwerLottoDocumentsBundle:Bet');

        $qb = $this->repo->createQueryBuilder("bet");
        $paginator = $this->get("qwer.pagination");
        $url = $this->generateUrl("bet");
        $entities = $paginator->getIterator($qb, $url, $page, 50);
        $html = $paginator->getHtml();
        return $this->render('QwerLottoDocumentsBundle:Bet:index.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $html,
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

        $body = new \Qwer\LottoDocumentsBundle\Entity\Request\Body();
        $collection = new \Doctrine\Common\Collections\ArrayCollection();
        for ($i = 0; $i < 2; $i++) {
            $value = new \Qwer\LottoDocumentsBundle\Entity\Request\RawBet();
            $collection->add($value);
        }
        $body->setRawBets($collection);
        $rawBetForm = $this->createForm(new \Qwer\LottoDocumentsBundle\Form\BodyType(), $body);

        return $this->render('QwerLottoDocumentsBundle:Bet:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'raw_bet_form' => $rawBetForm->createView(),
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

        $body = new \Qwer\LottoDocumentsBundle\Entity\Request\Body();
        $collection = new \Doctrine\Common\Collections\ArrayCollection();
        for ($i = 0; $i < 2; $i++) {
            $value = new \Qwer\LottoDocumentsBundle\Entity\Request\RawBet();
            $collection->add($value);
        }
        $body->setRawBets($collection);
        $rawBetForm = $this->createForm(new \Qwer\LottoDocumentsBundle\Form\BodyType(), $body);

        return $this->render('QwerLottoDocumentsBundle:Bet:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'raw_bet_form' => $rawBetForm->createView(),
                ));
    }

    /**
     * Finds and displays a Bet entity.
     *
     */
    public function showAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('QwerLottoDocumentsBundle:Bet')->findAll();

        if (!$entities) {
            throw $this->createNotFoundException('Unable to find Bet entity.');
        }

        return $this->render('QwerLottoDocumentsBundle:Bet:show.html.twig', array(
                    'entities' => $entities,
                ));
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

    public function betRequestAction(Request $request)
    {
        $body = new Body();
        $form = $this->createForm(new BodyType, $body);

        $form->bindRequest($request);
        $response = new \stdClass();
        
        if ($form->isValid()) {
           
            $dispatcher = $this->getEventDispatcher();

            $event = new BetRequestEvent();
            $event->setBody($body);
            try {
              //print("1555");
                $dispatcher->dispatch("bet.request.event", $event);
                $ids = array();
               
                foreach ($body->getBets() as $bet) {
                    $ids[] = $bet->getId();
                }
          
                $response->result = 'success';
                $response->ids = $ids;
                return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
                } 
            catch(\Qwer\LottoDocumentsBundle\Exception\BetRequestException $e){
                $message = $e->getErrorMessage();
                $response->errorMessage = $message;
            } catch (\Exception $e) {
                $message = $e->getMessage();
                $response->errorMessage = $message;
            }
        } else {
            $errors = $form->getErrors();
            foreach ($errors as $error) {
                $response->errorMessage = $error->getMessage() . "\n";
            }
        }
        $response->result = 'fail';
        return new \Symfony\Component\HttpFoundation\Response(json_encode($response));
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

    public function resultAction(Request $request, $id)
    {
        $calculation = $this->get("lotto.calculation");
        $message = 'Successful calculate';

        $draw = $this->getDoctrine()->getManager()
                ->getRepository("QwerLottoBundle:Draw")
                ->find($id);

        try {
           $message .= $calculation->calculate($draw);
        } catch (\Exception $e) {
            $message = toString($e);
        }
        $this->get('session')->getFlashBag()->add('notice', $message);

        return $this->redirect($this->generateUrl('draw_edit', array('id' => $id)));
    }

    public function rollbackAction(Request $request, $id)
    {
        $calculation = $this->get("lotto.calculation");
        $message = 'Successful rollback';

        $draw = $this->getDoctrine()->getManager()
                ->getRepository("QwerLottoBundle:Draw")
                ->find($id);

        try {
            $calculation->rallback($draw);
        } catch (\Exception $e) {
            $message = toString($e);
        }
        $this->get('session')->getFlashBag()->add('notice', $message);

        return $this->redirect($this->generateUrl('draw_edit', array('id' => $id)));
    }
    
    
    public function returnAction(Request $request, $id)
    {
        $calculation = $this->get("lotto.calculation");
        $message = 'Successful return';

        $draw = $this->getDoctrine()->getManager()
                ->getRepository("QwerLottoBundle:Draw")
                ->find($id);

        try {
            $this->rollbackAction($request, $id);
            $calculation->returnDraw($draw);
        } catch (\Exception $e) {
            $message = (string)$e;
        }
        $this->get('session')->getFlashBag()->add('notice', $message);

        return $this->redirect($this->generateUrl('draw_edit', array('id' => $id)));
    }

}
