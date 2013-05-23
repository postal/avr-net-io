<?php

namespace Ron\ConsumptionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ron\ConsumptionBundle\Entity\Energy;
use Ron\ConsumptionBundle\Form\EnergyType;

/**
 * Energy controller.
 *
 */
class EnergyController extends Controller
{
    /**
     * Lists all Energy entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('RonConsumptionBundle:Energy')->findAll();

        return $this->render('RonConsumptionBundle:Energy:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Energy entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Energy();
        $form = $this->createForm(new EnergyType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('energy_show', array('id' => $entity->getId())));
        }

        return $this->render('RonConsumptionBundle:Energy:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Energy entity.
     *
     */
    public function newAction()
    {
        $entity = new Energy();
        $form   = $this->createForm(new EnergyType(), $entity);

        return $this->render('RonConsumptionBundle:Energy:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Energy entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RonConsumptionBundle:Energy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Energy entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RonConsumptionBundle:Energy:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Energy entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RonConsumptionBundle:Energy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Energy entity.');
        }

        $editForm = $this->createForm(new EnergyType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RonConsumptionBundle:Energy:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Energy entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RonConsumptionBundle:Energy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Energy entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EnergyType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('energy_edit', array('id' => $id)));
        }

        return $this->render('RonConsumptionBundle:Energy:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Energy entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RonConsumptionBundle:Energy')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Energy entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('energy'));
    }

    /**
     * Creates a form to delete a Energy entity by id.
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
