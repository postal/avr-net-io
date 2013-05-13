<?php

namespace Ron\RaspberryPiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ron\RaspberryPiBundle\Entity\Gas;
use Ron\RaspberryPiBundle\Form\GasType;

/**
 * Gas controller.
 *
 */
class GasController extends Controller
{
    /**
     * Lists all Gas entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('RonRaspberryPiBundle:Gas')->findAll();

        return $this->render('RonRaspberryPiBundle:Gas:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Gas entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Gas();
        $form = $this->createForm(new GasType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('gas_show', array('id' => $entity->getId())));
        }

        return $this->render('RonRaspberryPiBundle:Gas:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Gas entity.
     *
     */
    public function newAction()
    {
        $entity = new Gas();
        $entity->setCreatedAt(new \DateTime());
        $form   = $this->createForm(new GasType(), $entity);

        return $this->render('RonRaspberryPiBundle:Gas:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Gas entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RonRaspberryPiBundle:Gas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Gas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RonRaspberryPiBundle:Gas:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Gas entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RonRaspberryPiBundle:Gas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Gas entity.');
        }

        $editForm = $this->createForm(new GasType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RonRaspberryPiBundle:Gas:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Gas entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RonRaspberryPiBundle:Gas')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Gas entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new GasType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('gas_edit', array('id' => $id)));
        }

        return $this->render('RonRaspberryPiBundle:Gas:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Gas entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RonRaspberryPiBundle:Gas')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Gas entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('gas'));
    }

    /**
     * Creates a form to delete a Gas entity by id.
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
