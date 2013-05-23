<?php

namespace Ron\ConsumptionBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ron\ConsumptionBundle\Entity\Water;
use Ron\ConsumptionBundle\Form\WaterType;

/**
 * Water controller.
 *
 */
class WaterController extends Controller
{
    /**
     * Lists all Water entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('RonConsumptionBundle:Water')->findAll();

        return $this->render('RonConsumptionBundle:Water:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Water entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Water();
        $form = $this->createForm(new WaterType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('water_show', array('id' => $entity->getId())));
        }

        return $this->render('RonConsumptionBundle:Water:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Water entity.
     *
     */
    public function newAction()
    {
        $entity = new Water();
        $form   = $this->createForm(new WaterType(), $entity);

        return $this->render('RonConsumptionBundle:Water:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Water entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RonConsumptionBundle:Water')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Water entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RonConsumptionBundle:Water:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Water entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RonConsumptionBundle:Water')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Water entity.');
        }

        $editForm = $this->createForm(new WaterType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RonConsumptionBundle:Water:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Water entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RonConsumptionBundle:Water')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Water entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new WaterType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('water_edit', array('id' => $id)));
        }

        return $this->render('RonConsumptionBundle:Water:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Water entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RonConsumptionBundle:Water')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Water entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('water'));
    }

    /**
     * Creates a form to delete a Water entity by id.
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
